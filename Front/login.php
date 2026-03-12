<?php
// Este é um arquivo PHP básico que contém o conteúdo HTML da tela de login
// Para adicionar funcionalidades dinâmicas, você precisaria implementar lógica PHP adicional
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Linha do Tempo Social</title>
    <!--script-- src="https://cdn.tailwindcss.com"></!--script-->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- 1. Estilo Global (Variáveis e Base) -->
    <link rel="stylesheet" href="css/global.css">

    <!-- Script Global deve vir primeiro para aplicar o tema cedo -->
    <script src="js/global.js"></script>

    <!-- 2. Estilo Específico da Página -->
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="p-8">
                <h1 class="cdi-text-2xl font-bold text-center mb-6">Bem-vindo</h1>

                <!-- Bloco de Mensagem de Erro -->
                <?php if (isset($_GET['erro'])): ?>
                    <div class="cdi-bg-danger-light border-l-4 cdi-text-danger p-3 mb-4 rounded shadow-sm animate-pulse" style="border-color: var(--danger-color);" role="alert">
                        <p class="cdi-text-sm font-bold"><i class="fas fa-exclamation-circle mr-2"></i> Usuário ou senha incorretos.</p>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['saiu'])): ?>
                    <div class="cdi-bg-success-light border-l-4 cdi-text-success p-3 mb-4 rounded shadow-sm" style="border-color: var(--success-color);">
                        <p class="cdi-text-sm font-bold"><i class="fas fa-check-circle mr-2"></i> Você saiu com segurança!</p>
                    </div>
                <?php endif; ?>

                <!-- Formulário de Login -->
                <form id="loginForm" action="includes/auth.php" method="POST" class="mt-4">
                    <div class="input-group">
                        <i class="input-icon fas fa-user"></i>
                        <input type="text" id="email" name="email" class="form-input" placeholder="Usuário ou Email" required>
                    </div>

                    <div class="input-group">
                        <i class="input-icon fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Senha" required>
                    </div>

                    <button type="submit" class="login-btn">
                        Entrar
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="js/login.js"></script>
</body>

</html>