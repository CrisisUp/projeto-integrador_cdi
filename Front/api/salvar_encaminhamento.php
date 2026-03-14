<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (!$dados || empty($dados['paciente_id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'A seleção do idoso é obrigatória.']);
    exit;
}

// VALIDAÇÃO DE DATA: Não permite datas retroativas
$hoje = date('Y-m-d');
if ($dados['data'] < $hoje) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'A data prevista não pode ser anterior ao dia de hoje.']);
    exit;
}

try {
    // Apenas validamos se o paciente existe, não precisamos mais salvar o nome em texto no banco
    $stmtP = $pdo->prepare("SELECT id FROM pacientes WHERE id = ?");
    $stmtP->execute([$dados['paciente_id']]);
    if (!$stmtP->fetch()) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Idoso não encontrado no sistema.']);
        exit;
    }

    $sql = "INSERT INTO encaminhamentos (paciente_id, data, urgencia, destino, usuario_id) 
            VALUES (:paciente_id, :data, :urgencia, :destino, :usuario_id)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':paciente_id'   => $dados['paciente_id'],
        ':data'          => $dados['data'],
        ':urgencia'      => $dados['urgencia'],
        ':destino'       => $dados['destino'],
        ':usuario_id'    => $_SESSION['usuario_id']
    ]);

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar no banco: ' . $e->getMessage()]);
}
?>
