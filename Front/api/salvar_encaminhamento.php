<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Sessão expirada.']);
    exit;
}

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (!$dados || empty($dados['paciente'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Nome do paciente é obrigatório.']);
    exit;
}

try {
    $sql = "INSERT INTO encaminhamentos (paciente, data, urgencia, destino, usuario_id) 
            VALUES (:paciente, :data, :urgencia, :destino, :usuario_id)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':paciente'   => $dados['paciente'],
        ':data'       => $dados['data'],
        ':urgencia'   => $dados['urgencia'],
        ':destino'    => $dados['destino'],
        ':usuario_id' => $_SESSION['usuario_id']
    ]);

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
