<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Sessão expirada.']);
    exit;
}

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (!$dados || empty($dados['paciente_id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'A seleção do idoso é obrigatória.']);
    exit;
}

// VALIDAÇÃO DE DATA: Não permite datas retroativas
$hoje = date('Y-m-d');
if ($dados['data'] < $hoje) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'A data prevista não pode ser anterior ao dia de hoje.']);
    exit;
}

try {
    $stmtP = $pdo->prepare("SELECT nome FROM pacientes WHERE id = ?");
    $stmtP->execute([$dados['paciente_id']]);
    $paciente = $stmtP->fetch();
    $nome_paciente = $paciente ? $paciente['nome'] : 'Desconhecido';

    $sql = "INSERT INTO encaminhamentos (paciente_id, paciente, data, urgencia, destino, usuario_id) 
            VALUES (:paciente_id, :paciente_nome, :data, :urgencia, :destino, :usuario_id)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':paciente_id'   => $dados['paciente_id'],
        ':paciente_nome' => $nome_paciente,
        ':data'          => $dados['data'],
        ':urgencia'      => $dados['urgencia'],
        ':destino'       => $dados['destino'],
        ':usuario_id'    => $_SESSION['usuario_id']
    ]);

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar no banco: ' . $e->getMessage()]);
}
?>
