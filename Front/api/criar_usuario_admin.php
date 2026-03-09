<?php
require_once __DIR__ . '/../includes/db.php';

$nome = "Administrador";
$email = "admin"; // Pode ser um email ou nome de usuário
$senha_pura = "123456";

// CRIPTOGRAFIA: O PHP gera um Hash impossível de reverter
$senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT OR REPLACE INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $senha_hash]);
    
    echo "<h1>✅ Usuário Administrador criado com sucesso!</h1>";
    echo "<p>Usuário: <b>admin</b></p>";
    echo "<p>Senha: <b>123456</b> (armazenada como Hash no banco)</p>";
    echo "<p>Hash gerado: <code>$senha_hash</code></p>";
} catch (PDOException $e) {
    echo "Erro ao criar usuário: " . $e->getMessage();
}
?>
