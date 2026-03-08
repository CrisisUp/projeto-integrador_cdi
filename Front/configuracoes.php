<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php"); // Se não estiver logado, volta pro login
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - CDI</title>
    <!-- Tailwind CSS -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- 1. Estilo Global (Variáveis e Base) -->
    <link rel="stylesheet" href="css/global.css">

    <!-- 2. SCRIPT GLOBAL (ESSENCIAL PARA O MODO ESCURO NÃO RESETAR) -->
    <script src="js/global.js"></script>

    <style>
        body {
            background-color: var(--bg-main, #f5f0e5);
            transition: background-color 0.3s ease;
        }

        .color-dot {
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 0 0 1px #e5e7eb;
            transition: transform 0.2s;
        }

        .color-dot:hover {
            transform: scale(1.15);
        }

        .color-dot.active {
            border-color: #374151;
        }
    </style>
</head>

<body class="bg-main min-h-screen flex flex-col md:flex-row overflow-x-hidden">

    <!-- INCLUSÃO DA SIDEBAR CENTRALIZADA -->
    <?php include 'sidebar.php'; ?>

    <!-- Conteúdo Principal -->
    <main class="flex-1 min-w-0 p-4 md:p-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Configurações</h1>
            <p class="text-gray-500">Personalize a aparência e o comportamento do seu sistema.</p>
        </header>

        <div class="max-w-4xl mx-auto space-y-8">

            <!-- SEÇÃO: APARÊNCIA -->
            <section class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden animate-fade-in">
                <div class="bg-purple-600 text-white px-6 py-4 flex items-center">
                    <i class="fas fa-palette mr-3 text-xl"></i>
                    <h2 class="text-lg font-semibold">Aparência e Temas</h2>
                </div>

                <div class="p-6 md:p-8 space-y-10">

                    <!-- Cores do Tema -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-4">Cor Principal do Sistema</h3>
                        <div class="flex flex-wrap gap-6">
                            <!-- Forçamos o background-color exato para o JS reconhecer -->
                            <div onclick="setThemeColor('#1d9bf0')" class="color-dot" style="background-color: #1d9bf0;" title="Azul Padrão"></div>
                            <div onclick="setThemeColor('#10b981')" class="color-dot" style="background-color: #10b981;" title="Verde"></div>
                            <div onclick="setThemeColor('#f59e0b')" class="color-dot" style="background-color: #f59e0b;" title="Âmbar"></div>
                            <div onclick="setThemeColor('#ef4444')" class="color-dot" style="background-color: #ef4444;" title="Vermelho"></div>
                            <div onclick="setThemeColor('#8b5cf6')" class="color-dot" style="background-color: #8b5cf6;" title="Roxo"></div>
                            <div onclick="setThemeColor('#374151')" class="color-dot" style="background-color: #374151;" title="Grafite"></div>
                        </div>
                        <p class="mt-4 text-sm text-gray-500">Esta cor será aplicada em botões, links e destaques em todas as páginas.</p>
                    </div>

                    <hr class="border-gray-100">

                    <!-- Modo Escuro -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Modo Escuro</h3>
                            <p class="text-sm text-gray-500 max-w-md">Inverte as cores de fundo e texto para proporcionar mais conforto visual em ambientes com pouca luz.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="dark-mode-switch" class="sr-only peer">
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                </div>
            </section>

            <!-- SEÇÃO: CONTA (Exemplo de futura implementação) -->
            <section class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden opacity-75">
                <div class="bg-gray-700 text-white px-6 py-4 flex items-center">
                    <i class="fas fa-user-shield mr-3 text-xl"></i>
                    <h2 class="text-lg font-semibold">Segurança da Conta</h2>
                </div>
                <div class="p-6 text-center text-gray-400 italic">
                    Funcionalidades de segurança serão liberadas na próxima atualização.
                </div>
            </section>

        </div>
    </main>

    <!-- Scripts de Funcionalidade -->
    <script src="js/configuracoes.js"></script>
</body>

</html>