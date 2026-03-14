<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$json_input = file_get_contents('php://input');
$dados = json_decode($json_input, true);

$id = $dados['id'] ?? null;

// 1. VALIDAÇÃO DE CAMPOS OBRIGATÓRIOS
if (!$id || empty($dados['nome']) || empty($dados['matricula'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID, Nome e Matrícula são necessários para atualização.']);
    exit;
}

try {
    // 2. VERIFICAÇÃO DE UNICIDADE DA MATRÍCULA
    // Verifica se a nova matrícula já pertence a OUTRO idoso (id diferente do atual)
    $stmtMat = $pdo->prepare("SELECT id FROM pacientes WHERE matricula = ? AND id != ?");
    $stmtMat->execute([$dados['matricula'], $id]);
    if ($stmtMat->fetch()) {
        echo json_encode(['status' => 'erro', 'mensagem' => "⚠️ A Matrícula [{$dados['matricula']}] já está em uso por outro idoso."]);
        exit;
    }

    // 3. VERIFICAÇÃO DE UNICIDADE DO NIS
    if (!empty($dados['nis'])) {
        $stmtNis = $pdo->prepare("SELECT id FROM pacientes WHERE nis = ? AND id != ?");
        $stmtNis->execute([$dados['nis'], $id]);
        if ($stmtNis->fetch()) {
            echo json_encode(['status' => 'erro', 'mensagem' => "⚠️ O NIS [{$dados['nis']}] já pertence a outro idoso cadastrado."]);
            exit;
        }
    }

    // 4. Prepara a query de atualização
    $sql = "UPDATE pacientes SET 
            matricula = :matricula,
            nome = :nome, 
            sexo = :sexo, 
            cor_raca = :cor_raca, 
            data_nascimento = :data_nascimento, 
            nis = :nis, 
            beneficios = :beneficios, 
            lanche_tipo_2 = :lanche_tipo_2,
            dieta_especial = :dieta_especial,
            status = :status,
            exibir_na_presenca = :exibir_na_presenca
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    
    $beneficios_json = isset($dados['beneficios']) ? json_encode($dados['beneficios']) : null;

    $stmt->execute([
        ':matricula'       => $dados['matricula'],
        ':nome'            => $dados['nome'],
        ':sexo'            => $dados['sexo'] ?? null,
        ':cor_raca'        => $dados['cor_raca'] ?? null,
        ':data_nascimento' => $dados['data_nascimento'] ?? null,
        ':nis'             => $dados['nis'] ?? null,
        ':beneficios'      => $beneficios_json,
        ':lanche_tipo_2'   => $dados['lanche_tipo_2'] ?? null,
        ':dieta_especial'  => $dados['dieta_especial'] ?? null,
        ':status'          => $dados['status'] ?? 'ativo',
        ':exibir_na_presenca' => $dados['exibir_na_presenca'] ?? 1,
        ':id'              => $id
    ]);

    registrarLog($pdo, 'UPDATE_PATIENT', 'pacientes', $id, "Nome: {$dados['nome']}");

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao atualizar: ' . $e->getMessage()]);
}
?>
