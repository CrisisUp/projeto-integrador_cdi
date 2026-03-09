<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

try {
    // Busca apenas pacientes ativos para a planilha de frequência
    $sql = "SELECT nome FROM pacientes WHERE status = 'ativo' ORDER BY nome ASC";
    $stmt = $pdo->query($sql);
    $nomes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($nomes);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>
