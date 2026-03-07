<?php
// Este é um arquivo PHP básico que contém o mesmo conteúdo HTML
// Para adicionar funcionalidades dinâmicas, você precisaria implementar lógica PHP adicional
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atividade Convencional</title>
    <!--script-- src="https://cdn.tailwindcss.com"></!--script-->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/convencional.css">
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
                        <p class="font-bold text-lg">Nome de Usuário</p>
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
                <a href="enfermagem.php" class="flex items-center p-3 text-xl sidebar-item rounded-full mx-2 mb-1">

                    <span>Atividade Enfermagem</span>
                </a>
                <a href="presenca.php" class="flex items-center p-3 text-xl sidebar-item rounded-full mx-2 mb-1">

                    <span>Frequência</span>
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
                    <h1 class="text-xl font-bold">Início</h1>
                </div>
                <div class="border-b border-gray-300">
                    <div class="py-4 text-center font-bold border-b-4 border-blue-500">Atividade Convencional</div>
                </div>
            </header>

            <!-- Formulário de Postagem -->
            <div class="border-b border-gray-300 p-4">
                <div class="flex">
                    <div class="w-12 h-12 rounded-full bg-blue-500 flex-shrink-0 flex items-center justify-center overflow-hidden">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <textarea class="w-full bg-transparent text-xl outline-none resize-none placeholder-gray-500 border-b border-gray-300 focus:border-blue-500" placeholder="O que está acontecendo?" rows="2"></textarea>
                        <div class="flex justify-between items-center mt-4">
                            <div class="flex space-x-4 text-blue-500">

                            </div>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition">
                                Postar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feed de Postagens -->
            <div id="posts-container">
                <!-- As postagens serão inseridas dinamicamente aqui -->
            </div>
        </div>

        <!-- Barra Lateral Direita -->
        <div class="w-80 p-4 border-l border-gray-300 hidden lg:block sticky top-0 h-screen overflow-y-auto">
            <!-- Pesquisa -->
            <div class="relative mb-6">
                <input type="text" placeholder="Pesquisar" class="w-full bg-white rounded-full py-3 px-5 pl-12 outline-none focus:ring-2 focus:ring-blue-400 shadow-sm">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-500"></i>
            </div>

            <!-- Calendário movido para abaixo da pesquisa -->
            <div class="mb-6 relative">
                <h2 class="text-xl font-bold mb-4">Filtrar por Data</h2>
                <div id="calendar" class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <button id="prevMonth" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h3 id="monthYear" class="text-lg font-bold cursor-pointer hover:text-blue-500"></h3>
                        <button id="nextMonth" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-7 gap-1 text-center text-sm mb-2">
                        <div class="text-gray-500">D</div>
                        <div class="text-gray-500">S</div>
                        <div class="text-gray-500">T</div>
                        <div class="text-gray-500">Q</div>
                        <div class="text-gray-500">Q</div>
                        <div class="text-gray-500">S</div>
                        <div class="text-gray-500">S</div>
                    </div>
                    <div id="calendarDays" class="grid grid-cols-7 gap-1 text-center"></div>
                </div>

                <!-- Seletor de Mês e Ano -->
                <div id="monthYearSelector" class="month-year-selector">
                    <div class="selector-tabs">
                        <div class="selector-tab active" data-tab="month">Mês</div>
                        <div class="selector-tab" data-tab="year">Ano</div>
                    </div>

                    <div id="monthSelector" class="month-grid">
                        <!-- Meses serão inseridos aqui dinamicamente -->
                    </div>

                    <div id="yearSelector" class="year-grid" style="display: none;">
                        <!-- Anos serão inseridos aqui dinamicamente -->
                    </div>
                </div>

                <div class="mt-4">
                    <div id="activeFilter" class="hidden bg-blue-100 p-3 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span>Filtrando por: <span id="filterDate" class="font-bold"></span></span>
                            <button id="clearFilter" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-times"></i> Limpar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/convencional.js"></script>
</body>

</html>