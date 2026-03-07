<?php
// Este é um arquivo PHP básico que contém o mesmo conteúdo HTML
// Para adicionar funcionalidades dinâmicas, você precisaria implementar lógica PHP adicional
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequência Diária</title>
    <!--script-- src="https://cdn.tailwindcss.com"></!--script-->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- 1. Estilo Global (Variáveis e Base) -->
    <link rel="stylesheet" href="css/global.css">

    <!-- 2. Estilo Específico da Página -->
    <link rel="stylesheet" href="css/presenca.css">
</head>

<body>
    <div class="min-h-screen flex">
        <!-- Barra Lateral Esquerda -->
        <div class="w-64 border-r border-gray-300 flex flex-col h-screen sticky top-0">
            <!-- Perfil do Usuário -->
            <div class="p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center overflow-hidden">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="font-bold text-lg">Usuário</p>
                    </div>
                </div>
            </div>

            <!-- Navegação -->
            <nav class="mb-4">
                <a href="#" class="flex items-center p-3 text-xl sidebar-item rounded-full mx-2 mb-1">
                    <i class="fas fa-home mr-4"></i>
                    <span>Início</span>
                </a>
                <a href="cadastro.php" class="flex items-center p-3 text-xl sidebar-item rounded-full mx-2 mb-1">

                    <span>Cadastro</span>
                </a>
                <a href="convencional.php" class="flex items-center p-3 text-xl sidebar-item rounded-full mx-2 mb-1">

                    <span>Atividade Convencional</span>
                </a>
                <a href="enfermagem.php" class="flex items-center p-3 text-xl sidebar-item rounded-full mx-2 mb-1">

                    <span>Atividade Enfermagem </span>
                </a>
                <a href="#" class="flex items-center p-3 text-xl sidebar-item rounded-full mx-2 mb-1">

                    <span>Encaminhamentos</span>
                </a>
                <a href="#" class="flex items-center p-3 text-xl sidebar-item rounded-full mx-2 mb-1">

                    <span>Configurações</span>
                </a>
                <a href="login.php" class="flex items-center p-3 text-xl sidebar-item rounded-full mx-2 mb-1 text-red-600">
                    <i class="fas fa-sign-out-alt mr-4"></i>
                    <span>Sair</span>
                </a>
            </nav>
        </div>

        <!-- Conteúdo Principal -->
        <div class="flex-1 min-w-0">
            <!-- Cabeçalho -->
            <header class="sticky top-0 bg-cream/80 backdrop-blur-md z-10 border-b border-gray-300 bg-opacity-90" style="background-color: rgba(245, 240, 229, 0.9);">
                <div class="p-4">
                    <h1 class="text-xl font-bold">Frequência Diária</h1>

                    <body>
                        <div class="app-container">
                            <div class="header-section">
                                <h1 class="text-2xl font-bold text-center mb-2 text-gray-800">Controle de Presença Mensal</h1>

                                <div class="mb-2 text-center">
                                    <p class="text-gray-600">Clique nas células para marcar presença</p>
                                    <p class="text-gray-500 text-xs mt-1">Sábados e domingos estão destacados com cores mais escuras</p>
                                </div>

                                <div id="month-selector" class="mb-2 flex justify-center">
                                    <button id="prev-month" class="px-2 py-1 bg-gray-200 rounded-l-lg hover:bg-gray-300 transition">
                                        &larr;
                                    </button>
                                    <div id="current-month" class="px-3 py-1 bg-gray-100 font-medium"></div>
                                    <button id="next-month" class="px-2 py-1 bg-gray-200 rounded-r-lg hover:bg-gray-300 transition">
                                        &rarr;
                                    </button>
                                </div>
                            </div>

                            <div class="grid-container bg-white rounded-xl shadow-lg p-2">
                                <div class="container-full">
                                    <table>
                                        <thead id="grid-header">
                                            <!-- Cabeçalho será gerado pelo JavaScript -->
                                        </thead>
                                        <tbody id="grid">
                                            <!-- Grid será gerado pelo JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="footer-section flex justify-center space-x-3">
                                <button id="reset-btn" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                                    Limpar Marcações
                                </button>
                                <button id="add-person-btn" class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm">
                                    Adicionar Pessoa
                                </button>
                            </div>
                        </div>
                        <script src="js/presenca.js"></script>
                    </body>

</html>