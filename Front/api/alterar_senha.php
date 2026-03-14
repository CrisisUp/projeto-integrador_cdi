<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';

// 2. Recebe os dados
$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

$senha_atual = $dados['senha_atual'] ?? '';
$nova_senha  = $dados['nova_senha'] ?? '';
$confirmacao = $dados['confirmacao'] ?? '';

// 3. Validações básicas
if (empty($senha_atual) || empty($nova_senha)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos.']);
    exit;
}

if ($nova_senha !== $confirmacao) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'A nova senha e a confirmação não conferem.']);
    exit;
}

if (strlen($nova_senha) < 6) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'A nova senha deve ter no mínimo 6 caracteres.']);
    exit;
}

try {
    // 4. Busca a senha atual no banco para validar
    $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($senha_atual, $usuario['senha'])) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'A senha atual está incorreta.']);
        exit;
    }

    // 5. Gera o novo Hash e atualiza o banco
    $novo_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $update = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $update->execute([$novo_hash, $_SESSION['usuario_id']]);

    echo json_encode(['status' => 'sucesso', 'mensagem' => 'Senha alterada com sucesso!']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao acessar o banco de dados.']);
}
?>
