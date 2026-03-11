<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

try {
    $hoje = date('Y-m-d');

    // 1. Idosos presentes hoje
    $stmt1 = $pdo->prepare("SELECT COUNT(*) FROM presenca WHERE data_presenca = ? AND status = 1");
    $stmt1->execute([$hoje]);
    $presentes_hoje = $stmt1->fetchColumn();

    // 2. Evoluções de enfermagem nas últimas 24h (Normalizado para evitar erros de maiúsculo/minúsculo)
    $stmt2 = $pdo->query("SELECT COUNT(*) FROM atividades 
                          WHERE LOWER(tipo) = 'enfermagem' 
                          AND data_postagem >= datetime('now', '-1 day', 'localtime')");
    $evolucoes_24h = $stmt2->fetchColumn();

    // 3. Encaminhamentos Urgentes/Alta pendentes (Normalizado para evitar erros de maiúsculo/minúsculo)
    $stmt3 = $pdo->query("SELECT COUNT(*) FROM encaminhamentos 
                          WHERE LOWER(status) = 'pendente' 
                          AND (LOWER(urgencia) = 'alta' OR LOWER(urgencia) = 'urgente')");
    $urgentes_pendentes = $stmt3->fetchColumn();

    // 4. Total de Idosos Ativos (Normalizado para evitar erros de maiúsculo/minúsculo)
    $stmt4 = $pdo->query("SELECT COUNT(*) FROM pacientes WHERE LOWER(status) = 'ativo'");
    $total_ativos = $stmt4->fetchColumn();

    echo json_encode([
        'status' => 'sucesso',
        'stats' => [
            'presentes' => $presentes_hoje,
            'evolucoes' => $evolucoes_24h,
            'urgentes'  => $urgentes_pendentes,
            'total'     => $total_ativos
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
