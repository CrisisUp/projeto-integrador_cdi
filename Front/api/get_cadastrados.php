<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

try {
    // Busca id e nome dos pacientes ativos
    $sql = "SELECT id, nome FROM pacientes WHERE status = 'ativo' ORDER BY nome ASC";
    $stmt = $pdo->query($sql);
    
    // Retorna como array de objetos {id, nome}
    $pacientes = $stmt->fetchAll();

    echo json_encode($pacientes);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>
