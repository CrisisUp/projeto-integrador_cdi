<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID do paciente não fornecido.']);
    exit;
}

try {
    // 1. Dados Cadastrais
    $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE id = ?");
    $stmt->execute([$id]);
    $paciente = $stmt->fetch();

    if (!$paciente) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Paciente não encontrado.']);
        exit;
    }

    // 2. Histórico de Atividades
    $stmtAtiv = $pdo->prepare("SELECT a.*, u.nome as funcionario 
                               FROM atividades a 
                               LEFT JOIN usuarios u ON a.usuario_id = u.id 
                               WHERE a.paciente_id = ? 
                               ORDER BY a.data_postagem DESC");
    $stmtAtiv->execute([$id]);
    $atividades = $stmtAtiv->fetchAll();

    // 3. Histórico de Encaminhamentos (NOVO)
    $stmtEnc = $pdo->prepare("SELECT e.*, u.nome as funcionario 
                              FROM encaminhamentos e 
                              LEFT JOIN usuarios u ON e.usuario_id = u.id 
                              WHERE e.paciente_id = ? 
                              ORDER BY e.data DESC");
    $stmtEnc->execute([$id]);
    $encaminhamentos = $stmtEnc->fetchAll();

    // 4. Resumo de Presença
    $stmtPres = $pdo->prepare("SELECT data_presenca, status FROM presenca WHERE paciente_id = ? ORDER BY data_presenca DESC LIMIT 30");
    $stmtPres->execute([$id]);
    $presencas = $stmtPres->fetchAll();

    echo json_encode([
        'status' => 'sucesso',
        'dados' => $paciente,
        'atividades' => $atividades,
        'encaminhamentos' => $encaminhamentos,
        'presencas' => $presencas
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
