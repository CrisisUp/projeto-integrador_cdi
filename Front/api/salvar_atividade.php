<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../includes/db.php';

// 1. Verifica se o usuário está logado (Auditoria)
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Sessão expirada.']);
    exit;
}

// 2. Recebe os dados
$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (!$dados || empty($dados['descricao'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Descrição é obrigatória']);
    exit;
}

try {
    // 3. Insere no Banco com o ID do usuário logado
    $sql = "INSERT INTO atividades (tipo, descricao, paciente_id, usuario_id) 
            VALUES (:tipo, :descricao, :paciente_id, :usuario_id)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':tipo'        => $dados['tipo'] ?? 'convencional',
        ':descricao'   => $dados['descricao'],
        ':paciente_id' => $dados['paciente_id'] ?? null,
        ':usuario_id'  => $_SESSION['usuario_id'] // ID do funcionário vindo da sessão
    ]);

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no banco: ' . $e->getMessage()]);
}
?>
