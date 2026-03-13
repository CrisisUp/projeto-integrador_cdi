<?php
require_once __DIR__ . '/../includes/db.php';

$json_file = __DIR__ . '/../data/pacientes.json';

if (!file_exists($json_file)) {
    die("Arquivo JSON não encontrado!");
}

$pacientes_json = json_decode(file_get_contents($json_file), true);

if (empty($pacientes_json)) {
    die("O arquivo JSON está vazio ou é inválido.");
}

$contador = 0;
$erros = 0;

// Prepara a query de inserção (evita SQL Injection)
$stmt = $pdo->prepare("INSERT OR IGNORE INTO pacientes 
    (matricula, nome, sexo, cor_raca, data_nascimento, nis, beneficios, cadastrado_em, status, exibir_na_presenca) 
    VALUES (:matricula, :nome, :sexo, :cor_raca, :data_nascimento, :nis, :beneficios, :cadastrado_em, :status, :exibir_na_presenca)");

foreach ($pacientes_json as $p) {
    // Trata o campo benefícios (se for array, vira string JSON)
    $beneficios = isset($p['beneficios']) ? json_encode($p['beneficios']) : null;
    
    try {
        $stmt->execute([
            ':matricula'       => $p['matricula'] ?? null,
            ':nome'            => $p['nome'] ?? 'Sem Nome',
            ':sexo'            => $p['sexo'] ?? null,
            ':cor_raca'        => $p['cor_raca'] ?? null,
            ':data_nascimento' => $p['data_nascimento'] ?? null,
            ':nis'             => $p['nis'] ?? null,
            ':beneficios'      => $beneficios,
            ':cadastrado_em'   => $p['cadastrado_em'] ?? date('Y-m-d H:i:s'),
            ':status'          => $p['status'] ?? 'ativo',
            ':exibir_na_presenca' => $p['exibir_na_presenca'] ?? 1
        ]);
        
        if ($stmt->rowCount() > 0) {
            $contador++;
        }
    } catch (PDOException $e) {
        $erros++;
    }
}

echo "Migração concluída!\n";
echo "Total de pacientes no JSON: " . count($pacientes_json) . "\n";
echo "Sucesso ao inserir no Banco: $contador\n";
echo "Duplicados ou erros: $erros\n";
?>
