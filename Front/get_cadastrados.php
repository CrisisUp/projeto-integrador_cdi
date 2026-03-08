<?php
$arquivo = 'cadastrados.txt';
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, "");
}

// Lê o arquivo e transforma cada linha em um item de um array
$nomes = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Retorna para o JavaScript em formato JSON
header('Content-Type: application/json');
echo json_encode($nomes);
