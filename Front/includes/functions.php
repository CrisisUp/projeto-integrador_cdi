<?php
/**
 * functions.php - Funções utilitárias globais do sistema.
 */

/**
 * Registra uma ação na trilha de auditoria (Logs).
 * 
 * @param PDO $pdo Instância da conexão com o banco.
 * @param string $acao Nome da ação (ex: 'LOGIN', 'CREATE_PATIENT').
 * @param string|null $tabela Nome da tabela afetada.
 * @param int|null $registro_id ID do registro afetado.
 * @param string|null $detalhes Descrição textual ou JSON com detalhes.
 */
function registrarLog($pdo, $acao, $tabela = null, $registro_id = null, $detalhes = null) {
    try {
        // Tenta pegar o ID do usuário da sessão
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        
        // Pega o IP do usuário
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $sql = "INSERT INTO logs (usuario_id, acao, tabela, registro_id, detalhes, ip_address) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario_id, $acao, $tabela, $registro_id, $detalhes, $ip]);
        
    } catch (Exception $e) {
        // Silencioso para não interromper o fluxo principal se o log falhar
        error_log("Falha ao registrar log: " . $e->getMessage());
    }
}

/**
 * Sanitiza uma string para evitar ataques XSS (Cross-Site Scripting).
 * Deve ser usada ao exibir dados vindos do banco ou antes de processar entradas de texto.
 * 
 * @param string $data
 * @return string
 */
function limparEntrada($data) {
    if (is_array($data)) {
        return array_map('limparEntrada', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Valida o CPF (Cadastro de Pessoas Físicas)
 * @param string $cpf
 * @return bool
 */
function validarCPF($cpf) {
    // Remove qualquer caractere que não seja número
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // O CPF deve ter 11 dígitos e não ser uma sequência repetida (111.111.111-11)
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Cálculo do primeiro dígito verificador
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

/**
 * Valida o NIS (Número de Identificação Social / PIS / PASEP)
 * O NIS tem 11 dígitos.
 * @param string $nis
 * @return bool
 */
function validarNIS($nis) {
    // Remove qualquer caractere que não seja número
    $nis = preg_replace('/[^0-9]/', '', $nis);

    if (strlen($nis) != 11 || preg_match('/(\d)\1{10}/', $nis)) {
        return false;
    }

    // Pesos oficiais da Caixa para o cálculo do NIS
    $pesos = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    $soma = 0;

    for ($i = 0; $i < 10; $i++) {
        $soma += $nis[$i] * $pesos[$i];
    }

    $resto = $soma % 11;
    $digito = ($resto < 2) ? 0 : 11 - $resto;

    return (int)$nis[10] === $digito;
}
?>
