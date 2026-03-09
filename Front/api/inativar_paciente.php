<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../includes/db.php';

// 1. Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Sessão expirada.']);
    exit;
}

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

if (!$dados || empty($dados['nome'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Nome não fornecido.']);
    exit;
}

try {
    // 2. Atualiza o status e registra quem fez a ação e quando
    $stmt = $pdo->prepare("UPDATE pacientes 
                           SET status = 'inativo', 
                               inativado_por = :usuario_id, 
                               inativado_em = :data_hora 
                           WHERE nome = :nome AND status = 'ativo'");
    
    $stmt->execute([
        ':usuario_id' => $_SESSION['usuario_id'],
        ':data_hora'  => date('Y-m-d H:i:s'),
        ':nome'       => $dados['nome']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'sucesso']);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Paciente não encontrado ou já está inativo.']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
