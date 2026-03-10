<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

$id = $dados['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID necessário para atualização.']);
    exit;
}

try {
    // Prepara a query dinâmica de atualização
    $sql = "UPDATE pacientes SET 
            nome = :nome, 
            sexo = :sexo, 
            cor_raca = :cor_raca, 
            data_nascimento = :data_nascimento, 
            nis = :nis, 
            beneficios = :beneficios, 
            status = :status 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    
    $beneficios_json = isset($dados['beneficios']) ? json_encode($dados['beneficios']) : null;

    $stmt->execute([
        ':nome'            => $dados['nome'],
        ':sexo'            => $dados['sexo'] ?? null,
        ':cor_raca'        => $dados['cor_raca'] ?? null,
        ':data_nascimento' => $dados['data_nascimento'] ?? null,
        ':nis'             => $dados['nis'] ?? null,
        ':beneficios'      => $beneficios_json,
        ':status'          => $dados['status'] ?? 'ativo',
        ':id'              => $id
    ]);

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
