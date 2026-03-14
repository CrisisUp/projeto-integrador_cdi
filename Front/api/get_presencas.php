<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';

$mes = $_GET['mes'] ?? date('m');
$ano = $_GET['ano'] ?? date('Y');

// Função auxiliar para completar com zero à esquerda (como str_pad em PHP)
function str_padStart($str, $len, $char) {
    return str_pad($str, $len, $char, STR_PAD_LEFT);
}

// Formata para o filtro (Ex: '2026-03-%')
$filtro_data = $ano . "-" . str_padStart($mes, 2, '0') . "-%";

try {
    // 1. Busca as presenças vinculadas aos pacientes (retornando o ID para maior precisão)
    $sql = "SELECT pr.paciente_id, p.nome, pr.data_presenca, pr.status 
            FROM presenca pr
            JOIN pacientes p ON p.id = pr.paciente_id
            WHERE pr.data_presenca LIKE :filtro";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':filtro' => $filtro_data]);
    $presencas = $stmt->fetchAll();

    // 2. Retorna para o JavaScript
    echo json_encode($presencas);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>
