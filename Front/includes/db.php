<?php
// Configuração do Banco de Dados SQLite
// O arquivo ficará guardado na pasta data/ (já protegida pelo .htaccess)
$db_file = __DIR__ . '/../data/clinica.db';

try {
    // Cria a conexão (se o arquivo não existir, o SQLite cria um novo)
    $pdo = new PDO("sqlite:" . $db_file);
    
    // Configura para mostrar erros caso algo dê errado
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Define o modo de busca padrão para Array Associativo
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
