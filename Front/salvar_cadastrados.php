<?php
if (isset($_POST['nome'])) {
    $nome = strip_tags($_POST['nome']); // Limpa o texto por segurança
    file_put_contents('cadastrados.txt', $nome . PHP_EOL, FILE_APPEND);
    echo "Sucesso";
}
