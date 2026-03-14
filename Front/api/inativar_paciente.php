<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (!$dados || empty($dados['nome'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Nome não fornecido.']);
    exit;
}

try {
    // 1. Busca o ID do paciente antes de inativar para o Log
    $stmtBusca = $pdo->prepare("SELECT id FROM pacientes WHERE nome = ? AND status = 'ativo' LIMIT 1");
    $stmtBusca->execute([$dados['nome']]);
    $paciente = $stmtBusca->fetch();

    if (!$paciente) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Paciente não encontrado ou já está inativo.']);
        exit;
    }

    $id = $paciente['id'];

    // 2. Atualiza o status e registra quem fez a ação e quando
    $stmt = $pdo->prepare("UPDATE pacientes 
                           SET status = 'inativo', 
                               inativado_por = :usuario_id, 
                               inativado_em = :data_hora 
                           WHERE id = :id");
    
    $stmt->execute([
        ':usuario_id' => $_SESSION['usuario_id'],
        ':data_hora'  => date('Y-m-d H:i:s'),
        ':id'         => $id
    ]);

    registrarLog($pdo, 'INACTIVATE_PATIENT', 'pacientes', $id, "Nome: {$dados['nome']}");

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
