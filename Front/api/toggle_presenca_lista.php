<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (!$dados || !isset($dados['id']) || !isset($dados['exibir'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE pacientes SET exibir_na_presenca = ? WHERE id = ?");
    $stmt->execute([$dados['exibir'], $dados['id']]);
    echo json_encode(['status' => 'sucesso']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
