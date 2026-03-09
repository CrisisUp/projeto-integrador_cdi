<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

// 1. Recebe os dados
$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (!$dados || empty($dados['nome_paciente']) || empty($dados['data'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos']);
    exit;
}

try {
    // 2. Primeiro, precisamos achar o ID do paciente pelo nome (já que a tabela presenca usa paciente_id)
    $stmtId = $pdo->prepare("SELECT id FROM pacientes WHERE nome = :nome LIMIT 1");
    $stmtId->execute([':nome' => $dados['nome_paciente']]);
    $paciente = $stmtId->fetch();

    if (!$paciente) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Paciente não encontrado']);
        exit;
    }

    $paciente_id = $paciente['id'];
    $data = $dados['data'];
    $status = $dados['status']; // 0 ou 1

    // 3. Verifica se já existe um registro para esse paciente nesta data
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

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no banco: ' . $e->getMessage()]);
}
?>
