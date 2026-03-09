<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

$tipo = $_GET['tipo'] ?? 'convencional';
$data = $_GET['data'] ?? null;

try {
    // 1. Constrói a Query com JOIN para pegar o nome do usuário
    $sql = "SELECT a.*, u.nome as funcionario_nome 
            FROM atividades a
            LEFT JOIN usuarios u ON a.usuario_id = u.id
            WHERE a.tipo = :tipo";
    
    $params = [':tipo' => $tipo];

    if ($data) {
        $sql .= " AND DATE(a.data_postagem) = :data";
        $params[':data'] = $data;
    }

    $sql .= " ORDER BY a.data_postagem DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $atividades = $stmt->fetchAll();

    // 2. Retorna para o JavaScript
    echo json_encode($atividades);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>
