<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (!$dados || empty($dados['nome'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Nome não fornecido.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE pacientes SET status = 'inativo' WHERE nome = ?");
    $stmt->execute([$dados['nome']]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'sucesso']);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Paciente não encontrado.']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
