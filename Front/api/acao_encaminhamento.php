<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

$id    = $dados['id'] ?? null;
$acao  = $dados['acao'] ?? ''; // 'concluir' ou 'excluir'

if (!$id) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID inválido.']);
    exit;
}

try {
    if ($acao === 'concluir') {
        $stmt = $pdo->prepare("UPDATE encaminhamentos SET status = 'Concluído' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($acao === 'excluir') {
        $stmt = $pdo->prepare("DELETE FROM encaminhamentos WHERE id = ?");
        $stmt->execute([$id]);
    }

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
