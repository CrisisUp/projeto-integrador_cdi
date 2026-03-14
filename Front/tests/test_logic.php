<?php
/**
 * TESTES AUTOMATIZADOS: Validações de CPF e NIS
 * Rode via terminal: php Front/tests/test_logic.php
 */

require_once __DIR__ . '/../includes/functions.php';

// Contador de sucessos e falhas
$sucesso = 0;
$falha = 0;

function assertTest($descricao, $resultado) {
    global $sucesso, $falha;
    if ($resultado === true) {
        echo "✅ OK: $descricao\n";
        $sucesso++;
    } else {
        echo "❌ FALHA: $descricao\n";
        $falha++;
    }
}

echo "=== INICIANDO TESTES DE NEGÓCIO ===\n\n";

// --- TESTES DE CPF ---
echo "--- Testando validarCPF() ---\n";
assertTest("CPF Válido (Formato Limpo)", validarCPF('12345678909') === true);
assertTest("CPF Válido (Formato Máscara)", validarCPF('123.456.789-09') === true);
assertTest("CPF Inválido (Dígito Errado)", validarCPF('12345678900') === false);
assertTest("CPF Inválido (Sequência Repetida)", validarCPF('11111111111') === false);
assertTest("CPF Inválido (Tamanho Curto)", validarCPF('123456') === false);

// --- TESTES DE NIS ---
echo "\n--- Testando validarNIS() ---\n";
// NIS Válido real para teste (PIS/PASEP/NIS)
assertTest("NIS Válido (Formato Limpo)", validarNIS('17033259504') === true); 
assertTest("NIS Válido (Formato Máscara)", validarNIS('170.33259.50-4') === true);
assertTest("NIS Inválido (Dígito Errado)", validarNIS('17033259500') === false);
assertTest("NIS Inválido (Sequência Repetida)", validarNIS('00000000000') === false);
assertTest("NIS Inválido (Tamanho Curto)", validarNIS('12345') === false);

echo "\n===============================\n";
echo "RESULTADOS:\n";
echo "Sucessos: $sucesso\n";
echo "Falhas: $falha\n";
echo "===============================\n";

if ($falha > 0) {
    exit(1); // Retorna erro para CI/CD
} else {
    exit(0); // Retorna sucesso
}
?>
