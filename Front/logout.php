<?php
// 1. Inicia a sessão para poder manipulá-la
session_start();

// 2. Limpa todas as variáveis de sessão
$_SESSION = array();

// 3. Se desejar destruir o cookie de sessão no navegador do usuário (mais seguro)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Destrói a sessão no servidor
session_destroy();

// 5. Redireciona para a tela de login
header("Location: login.php?saiu=1");
exit();
