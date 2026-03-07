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
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f5f0e5;
            /* Fundo creme */
            color: #333;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .input-group {
            position: relative;
            margin-bottom: 24px;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px 12px 48px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .form-input:focus {
            border-color: #1d9bf0;
            box-shadow: 0 0 0 3px rgba(29, 155, 240, 0.2);
            outline: none;
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #1d9bf0;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .login-btn:hover {
            background-color: #1a8cd8;
        }

        .login-btn:active {
            transform: scale(0.98);
        }

        /* Animação de entrada */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeIn 0.6s ease-out;
        }

        /* Barra de rolagem personalizada */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f5f0e5;
        }

        ::-webkit-scrollbar-thumb {
            background: #d6cdb7;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #c2b89f;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="p-8">
                <h1 class="text-2xl font-bold text-center mb-6">Bem-vindo</h1>

                <!-- Formulário de Login -->
                <form id="loginForm" class="mt-8">
                    <div class="input-group">
                        <i class="input-icon fas fa-envelope"></i>
                        <input type="email" id="email" name="email" class="form-input" placeholder="Nome de usuário" required>
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

    <script>
        // Manipulação do formulário de login
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Obter valores dos campos
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Aqui você adicionaria a lógica de autenticação
            // Por exemplo, enviar os dados para um script PHP via AJAX

            // Simulação de login bem-sucedido
            console.log('Tentativa de login com:', {
                email,
                password
            });

            // Redirecionar para a página principal após login bem-sucedido
            // Na implementação real, isso seria feito após verificar as credenciais
            setTimeout(() => {
                alert('Login bem-sucedido! Redirecionando...');
                // window.location.href = 'index.php'; // Descomentar para redirecionar
                window.location.href = 'navegacao.php';
            }, 1000);
        });

        // Animação nos campos de entrada
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#1d9bf0';
            });

            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#9ca3af';
            });
        });
    </script>
    <!--
    <script>
        (function() {
            function c() {
                var b = a.contentDocument || a.contentWindow.document;
                if (b) {
                    var d = b.createElement('script');
                    d.innerHTML = "window.__CF$cv$params={r:'93abc31ae48f1a8c',t:'MTc0NjQwMTE3Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
                    b.getElementsByTagName('head')[0].appendChild(d)
                }
            }
            if (document.body) {
                var a = document.createElement('iframe');
                a.height = 1;
                a.width = 1;
                a.style.position = 'absolute';
                a.style.top = 0;
                a.style.left = 0;
                a.style.border = 'none';
                a.style.visibility = 'hidden';
                document.body.appendChild(a);
                if ('loading' !== document.readyState) c();
                else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c);
                else {
                    var e = document.onreadystatechange || function() {};
                    document.onreadystatechange = function(b) {
                        e(b);
                        'loading' !== document.readyState && (document.onreadystatechange = e, c())
                    }
                }
            }
        })();
    </script>
    -->
</body>

</html>