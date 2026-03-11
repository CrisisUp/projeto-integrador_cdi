<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

try {
    // Busca id, nome, matrícula e status de exibição dos pacientes ativos
    $sql = "SELECT id, nome, matricula, exibir_na_presenca FROM pacientes WHERE LOWER(status) = 'ativo' ORDER BY nome ASC";
    $stmt = $pdo->query($sql);
    
    // Retorna como array de objetos {id, nome, matricula, exibir_na_presenca}
    $pacientes = $stmt->fetchAll();

    echo json_encode($pacientes);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>
