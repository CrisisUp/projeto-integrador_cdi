<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';

/**
 * get_dashboard_stats.php - Versão Realística e Crítica
 * Cruza dados de presença com evoluções para garantir que ninguém seja esquecido.
 */

try {
    // 1. Definição da data de hoje (Horário Local)
    $hoje = date('Y-m-d');

    // 2. IDOSOS ATIVOS E ESPERADOS HOJE
    // Quantos idosos estão marcados como ativos e devem aparecer na lista de presença?
    $stmtAtivos = $pdo->query("SELECT COUNT(*) FROM pacientes WHERE LOWER(status) = 'ativo'");
    $total_ativos = $stmtAtivos->fetchColumn();

    $stmtEsperados = $pdo->query("SELECT COUNT(*) FROM pacientes WHERE LOWER(status) = 'ativo' AND exibir_na_presenca = 1");
    $total_esperados = $stmtEsperados->fetchColumn();

    // 3. FREQUÊNCIA REALIZADA
    // Quantos foram marcados como presentes (status 1) hoje?
    $stmtPresentes = $pdo->prepare("SELECT COUNT(*) FROM presenca WHERE data_presenca = ? AND status = 1");
    $stmtPresentes->execute([$hoje]);
    $presentes_hoje = $stmtPresentes->fetchColumn();

    // 4. EVOLUÇÕES DE ENFERMAGEM (GESTÃO CRÍTICA)
    // Quantos idosos QUE ESTÃO PRESENTES hoje já receberam sua evolução de enfermagem?
    // Isso garante que a estatística não conte idosos que não vieram.
    $sqlEvolucoes = "SELECT COUNT(DISTINCT a.paciente_id) 
                     FROM atividades a
                     JOIN presenca p ON a.paciente_id = p.paciente_id
                     WHERE LOWER(a.tipo) = 'enfermagem' 
                     AND DATE(a.data_postagem) = :hoje
                     AND p.data_presenca = :hoje 
                     AND p.status = 1";
    
    $stmtEvol = $pdo->prepare($sqlEvolucoes);
    $stmtEvol->execute([':hoje' => $hoje]);
    $evolucoes_realizadas = $stmtEvol->fetchColumn();

    // 5. ENCAMINHAMENTOS URGENTES
    $stmtUrgentes = $pdo->query("SELECT COUNT(*) FROM encaminhamentos 
                                 WHERE LOWER(status) = 'pendente' 
                                 AND (LOWER(urgencia) = 'alta' OR LOWER(urgencia) = 'urgente')");
    $urgentes_pendentes = $stmtUrgentes->fetchColumn();

    // 6. RETORNO DOS DADOS
    echo json_encode([
        'status' => 'sucesso',
        'stats' => [
            'presentes' => (int)$presentes_hoje,
            'total_esperado' => (int)$total_esperados,
            'evolucoes' => (int)$evolucoes_realizadas,
            'evolucoes_pendentes' => (int)($presentes_hoje - $evolucoes_realizadas),
            'urgentes' => (int)$urgentes_pendentes,
            'total_ativos' => (int)$total_ativos
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
}
?>
