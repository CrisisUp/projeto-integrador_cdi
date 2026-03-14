<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// 1. Recebe os dados
$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (!$dados || empty($dados['paciente_id']) || empty($dados['data'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos']);
    exit;
}

try {
    $paciente_id = $dados['paciente_id'];
    $data = $dados['data'];
    $status = $dados['status']; // 0 ou 1

    // 2. Verifica se já existe um registro para esse paciente nesta data
    $stmtCheck = $pdo->prepare("SELECT id FROM presenca WHERE paciente_id = :pid AND data_presenca = :data");
    $stmtCheck->execute([':pid' => $paciente_id, ':data' => $data]);
    $existente = $stmtCheck->fetch();

    if ($existente) {
        // Se já existe, atualizamos o status
        $stmtUpd = $pdo->prepare("UPDATE presenca SET status = :status WHERE id = :id");
        $stmtUpd->execute([':status' => $status, ':id' => $existente['id']]);
    } else {
        // Se não existe, inserimos um novo
        $stmtIns = $pdo->prepare("INSERT INTO presenca (paciente_id, data_presenca, status) VALUES (:pid, :data, :status)");
        $stmtIns->execute([':pid' => $paciente_id, ':data' => $data, ':status' => $status]);
    }

    registrarLog($pdo, 'SAVE_ATTENDANCE', 'presenca', $paciente_id, "Status: " . ($status ? 'Presente' : 'Ausente') . " na data: $data");

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no banco: ' . $e->getMessage()]);
}
?>
