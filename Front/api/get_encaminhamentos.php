<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

$busca  = $_GET['busca'] ?? '';
$status = $_GET['status'] ?? 'Todos os Status';

try {
    $sql = "SELECT e.*, u.nome as funcionario_nome 
            FROM encaminhamentos e 
            LEFT JOIN usuarios u ON e.usuario_id = u.id 
            WHERE 1=1";
    
    $params = [];

    if ($busca !== '') {
        $sql .= " AND e.paciente LIKE :busca";
        $params[':busca'] = "%$busca%";
    }

    if ($status !== 'Todos os Status') {
        $sql .= " AND e.status = :status";
        $params[':status'] = $status;
    }

    $sql .= " ORDER BY e.status ASC, e.data ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode($stmt->fetchAll());

} catch (PDOException $e) {
    echo json_encode([]);
}
?>
