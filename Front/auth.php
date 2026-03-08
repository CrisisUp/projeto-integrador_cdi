<?php
// 1. Inicia a sessão - Isso deve ser a primeira coisa no arquivo!
session_start();

// 2. Define o usuário e senha autorizados (Simulação de Banco de Dados)
// No futuro, você buscará esses valores do MySQL
$usuario_valido = "admin";
$senha_valida = "123456"; // Senha simples para teste

// 3. Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Captura os dados do formulário (usando os 'name' que definimos no HTML)
    $email_digitado = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha_digitada = isset($_POST['password']) ? trim($_POST['password']) : '';

    // 4. Validação das Credenciais
    if ($email_digitado === $usuario_valido && $senha_digitada === $senha_valida) {

        // SUCESSO! 
        // Criamos as variáveis de sessão que "autorizam" o acesso
        $_SESSION['logado'] = true;
        $_SESSION['usuario_nome'] = $usuario_valido;
        $_SESSION['id_sessao'] = session_id();
        $_SESSION['ultimo_acesso'] = time();

        // Redireciona para a página principal
        header("Location: navegacao.php");
        exit(); // Interrompe o script após o redirecionamento

    } else {
        // FALHA!
        // Redireciona de volta para o login com um parâmetro de erro na URL
        header("Location: login.php?erro=1");
        exit();
    }
} else {
    // Se tentarem acessar este arquivo diretamente sem o formulário
    header("Location: login.php");
    exit();
}
