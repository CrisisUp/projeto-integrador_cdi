<?php
header('Content-Type: application/json');

// 1. Recebe o JSON do JavaScript
$json_input = file_get_contents('php://input');
$novo_paciente = json_decode($json_input, true);

if (!$novo_paciente || empty($novo_paciente['nome'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados inválidos']);
    exit;
}

$arquivo = 'pacientes.json';

// 2. Lê a lista atual (se o arquivo existir)
$lista = [];
if (file_exists($arquivo)) {
    $conteudo = file_get_contents($arquivo);
    $lista = json_decode($conteudo, true) ?? [];
}

// 3. Adiciona o novo paciente com um ID único e data de cadastro
$novo_paciente['id'] = uniqid();
$novo_paciente['cadastrado_em'] = date('Y-m-d H:i:s');
$lista[] = $novo_paciente;

// 4. Salva no arquivo com formatação organizada
$sucesso = file_put_contents($arquivo, json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if ($sucesso) {
    // OPCIONAL: Atualizar o idosos.txt para a Frequência continuar funcionando
    file_put_contents('cadastrados.txt', $novo_paciente['nome'] . PHP_EOL, FILE_APPEND);

    echo json_encode(['status' => 'sucesso']);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Falha ao escrever no arquivo']);
}
