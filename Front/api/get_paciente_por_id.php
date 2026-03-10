<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID não fornecido.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE id = ?");
    $stmt->execute([$id]);
    $paciente = $stmt->fetch();

    if ($paciente) {
        // Decodifica benefícios se for JSON
        if ($paciente['beneficios']) {
            $paciente['beneficios'] = json_decode($paciente['beneficios'], true);
        }
        echo json_encode(['status' => 'sucesso', 'paciente' => $paciente]);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Paciente não encontrado.']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
