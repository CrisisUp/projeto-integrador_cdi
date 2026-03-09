<?php
// 1. Inicia a sessão
session_start();
require_once __DIR__ . '/db.php';

// 2. Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Captura os dados do formulário
    $email_digitado = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha_digitada = isset($_POST['password']) ? trim($_POST['password']) : '';

    try {
        // 3. Busca o usuário no Banco de Dados
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email_digitado]);
        $usuario = $stmt->fetch();

        // 4. Validação das Credenciais
        // password_verify compara a senha digitada com o Hash do banco
        if ($usuario && password_verify($senha_digitada, $usuario['senha'])) {

            // SUCESSO! 
            $_SESSION['logado'] = true;
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['ultimo_acesso'] = time();

            // Redireciona para a página principal
            header("Location: ../pages/navegacao.php");
            exit();

        } else {
            // FALHA! Usuário não existe ou senha não bate
            header("Location: ../login.php?erro=1");
            exit();
        }

    } catch (PDOException $e) {
        die("Erro técnico no sistema de login.");
    }

} else {
    // Acesso direto via URL
    header("Location: ../login.php");
    exit();
}
?>
