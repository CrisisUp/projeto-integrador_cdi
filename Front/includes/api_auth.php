<?php
/**
 * api_auth.php - Proteção de segurança para endpoints da API.
 * Verifica se existe uma sessão ativa e valida o token CSRF em operações de escrita.
 */
session_start();

// 1. Verifica se o usuário está logado (Obrigatório para todas as chamadas)
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Content-Type: application/json');
    http_response_code(401); 
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Acesso negado. Autenticação necessária.'
    ]);
    exit;
}

// 2. Valida o Token CSRF apenas para métodos que alteram dados (POST)
// Isso evita o erro 403 em requisições de leitura (GET)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_recebido = null;
    $headers = getallheaders();

    if (isset($headers['X-CSRF-Token'])) {
        $csrf_recebido = $headers['X-CSRF-Token'];
    } elseif (isset($headers['x-csrf-token'])) {
        $csrf_recebido = $headers['x-csrf-token'];
    }

    if (!$csrf_recebido || $csrf_recebido !== $_SESSION['csrf_token']) {
        header('Content-Type: application/json');
        http_response_code(403); // Proibido
        echo json_encode([
            'status' => 'erro',
            'mensagem' => 'Falha na validação de segurança (CSRF Token inválido).'
        ]);
        exit;
    }
}
?>
