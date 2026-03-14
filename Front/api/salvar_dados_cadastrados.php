<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$json_input = file_get_contents('php://input');
$novo_paciente = json_decode($json_input, true);

// 1. VALIDAÇÃO DE CAMPOS OBRIGATÓRIOS
if (!$novo_paciente || empty($novo_paciente['nome']) || empty($novo_paciente['matricula'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Nome e Matrícula são campos obrigatórios.']);
    exit;
}

// SANITIZAÇÃO DE ENTRADAS (Prevenção XSS)
$nome      = limparEntrada($novo_paciente['nome']);
$matricula = limparEntrada($novo_paciente['matricula']);
$nis       = !empty($novo_paciente['nis']) ? limparEntrada($novo_paciente['nis']) : null;

try {
    // 2. VERIFICAÇÃO DE UNICIDADE (Matrícula)
    $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE matricula = ?");
    $stmt->execute([$matricula]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'erro', 'mensagem' => "⚠️ A Matrícula [{$matricula}] já está cadastrada para outro idoso."]);
        exit;
    }

    // 3. VERIFICAÇÃO DE UNICIDADE E VALIDADE (NIS) - Se preenchido
    if (!empty($nis)) {
        // Valida o formato/dígito do NIS
        if (!validarNIS($nis)) {
            echo json_encode(['status' => 'erro', 'mensagem' => "⚠️ O NIS [{$nis}] informado é inválido."]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE nis = ?");
        $stmt->execute([$nis]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'erro', 'mensagem' => "⚠️ O NIS [{$nis}] já pertence a um idoso cadastrado."]);
            exit;
        }
    }

    // 4. INSERÇÃO
    $sql = "INSERT INTO pacientes (matricula, nome, sexo, cor_raca, data_nascimento, nis, beneficios, lanche_tipo_2, dieta_especial, status, exibir_na_presenca) 
            VALUES (:matricula, :nome, :sexo, :cor_raca, :data_nascimento, :nis, :beneficios, :lanche_tipo_2, :dieta_especial, :status, :exibir_na_presenca)";
    
    $stmt = $pdo->prepare($sql);
    $beneficios_json = isset($novo_paciente['beneficios']) ? json_encode($novo_paciente['beneficios']) : null;

    $stmt->execute([
        ':matricula'       => $matricula,
        ':nome'            => $nome,
        ':sexo'            => $novo_paciente['sexo'] ?? null,
        ':cor_raca'        => $novo_paciente['cor_raca'] ?? null,
        ':data_nascimento' => $novo_paciente['data_nascimento'] ?? null,
        ':nis'             => $nis,
        ':beneficios'      => $beneficios_json,
        ':lanche_tipo_2'   => $novo_paciente['lanche_tipo_2'] ?? null,
        ':dieta_especial'  => $novo_paciente['dieta_especial'] ?? null,
        ':status'          => $novo_paciente['status'] ?? 'ativo',
        ':exibir_na_presenca' => $novo_paciente['exibir_na_presenca'] ?? 1
    ]);

    $id_novo = $pdo->lastInsertId();
    registrarLog($pdo, 'CREATE_PATIENT', 'pacientes', $id_novo, "Nome: {$nome}, Matrícula: {$matricula}");

    echo json_encode(['status' => 'sucesso']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro interno no banco.']);
}
?>
