<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

// 1. Recebe os dados
$json_input = file_get_contents('php://input');
$novo_paciente = json_decode($json_input, true);

if (!$novo_paciente || empty($novo_paciente['nome'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados inválidos']);
    exit;
}

try {
    // 2. VERIFICAÇÃO DE UNICIDADE (Matrícula e NIS)
    
    // Verifica Matrícula
    if (!empty($novo_paciente['matricula'])) {
        $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE matricula = ?");
        $stmt->execute([$novo_paciente['matricula']]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'erro', 'mensagem' => "⚠️ A Matrícula [{$novo_paciente['matricula']}] já está cadastrada para outro idoso."]);
            exit;
        }
    }

    // Verifica NIS
    if (!empty($novo_paciente['nis'])) {
        $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE nis = ?");
        $stmt->execute([$novo_paciente['nis']]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'erro', 'mensagem' => "⚠️ O NIS [{$novo_paciente['nis']}] já pertence a um idoso cadastrado."]);
            exit;
        }
    }

    // 3. Prepara a inserção se passar nas verificações
    $sql = "INSERT INTO pacientes (matricula, nome, sexo, cor_raca, data_nascimento, nis, beneficios) 
            VALUES (:matricula, :nome, :sexo, :cor_raca, :data_nascimento, :nis, :beneficios)";
    
    $stmt = $pdo->prepare($sql);
    
    $beneficios_json = isset($novo_paciente['beneficios']) ? json_encode($novo_paciente['beneficios']) : null;

    $stmt->execute([
        ':matricula'       => $novo_paciente['matricula'] ?? null,
        ':nome'            => $novo_paciente['nome'],
        ':sexo'            => $novo_paciente['sexo'] ?? null,
        ':cor_raca'        => $novo_paciente['cor_raca'] ?? null,
        ':data_nascimento' => $novo_paciente['data_nascimento'] ?? null,
        ':nis'             => $novo_paciente['nis'] ?? null,
        ':beneficios'      => $beneficios_json
    ]);

    // 4. Retorna sucesso
    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro interno no banco: ' . $e->getMessage()]);
}
?>
