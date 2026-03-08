<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atividade Convencional - CDI</title>

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- 1. Estilo Global -->
    <link rel="stylesheet" href="css/global.css">
    <script src="js/global.js"></script>

    <!-- 2. Estilo Específico -->
    <link rel="stylesheet" href="css/convencional.css">
</head>

<body class="bg-main min-h-screen flex flex-col md:flex-row">

    <!-- INCLUSÃO DA SIDEBAR ÚNICA -->
    <?php include 'sidebar.php'; ?>

    <!-- Conteúdo Principal -->
    <div class="flex-1 min-w-0 flex">

        <!-- Coluna do Feed (Centro) -->
        <div class="flex-1 border-r border-gray-300 flex flex-col">

            <!-- Cabeçalho com Glassmorphism -->
            <header class="sticky top-0 z-10 border-b border-gray-300 glass-header" style="background-color: rgba(245, 240, 229, 0.9); backdrop-filter: blur(8px);">
                <div class="p-4">
                    <h1 class="text-xl font-bold text-gray-800">Atividades</h1>
                </div>
                <div class="bg-white/50">
                    <div class="py-4 text-center font-bold border-b-4 border-green-500 text-green-600 uppercase text-xs tracking-widest">
                        Atividade Convencional
                    </div>
                </div>
            </header>

            <!-- Formulário de Postagem de Atividades -->
            <div class="border-b border-gray-300 p-6 bg-white/30">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-500 flex-shrink-0 flex items-center justify-center text-white shadow-sm">
                        <i class="fas fa-clipboard-list text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <textarea class="w-full bg-transparent text-lg outline-none resize-none placeholder-gray-400 border-b border-transparent focus:border-green-500 transition-all"
                            placeholder="Descreva a atividade realizada hoje..." rows="2"></textarea>

                        <div class="flex justify-between items-center mt-4">
                            <div class="flex space-x-4 text-gray-400">
                                <button class="hover:text-green-500 transition" title="Adicionar Foto"><i class="fas fa-camera"></i></button>
                                <button class="hover:text-green-500 transition" title="Marcar Participantes"><i class="fas fa-users"></i></button>
                            </div>
                            <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-8 rounded-xl transition shadow-md transform hover:-translate-y-1">
                                Postar Atividade
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feed de Postagens -->
            <div id="posts-container" class="divide-y divide-gray-100 overflow-y-auto">
                <!-- Injetado pelo js/convencional.js -->
            </div>
        </div>

        <!-- Barra Lateral Direita (Widgets) -->
        <div class="w-80 p-6 hidden lg:block sticky top-0 h-screen overflow-y-auto bg-gray-50/30">

            <!-- Busca -->
            <div class="relative mb-8">
                <input type="text" placeholder="Pesquisar atividades..."
                    class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-5 pl-12 outline-none focus:ring-2 focus:ring-green-400 shadow-sm transition">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-300"></i>
            </div>

            <!-- Calendário de Filtro -->
            <div class="mb-6">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 px-2">Filtrar por Data</h2>
                <div id="calendar" class="bg-white rounded-3xl p-5 shadow-card border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <button id="prevMonth" class="text-green-600 hover:bg-green-50 p-2 rounded-lg"><i class="fas fa-chevron-left"></i></button>
                        <h3 id="monthYear" class="font-bold text-gray-700 cursor-pointer"></h3>
                        <button id="nextMonth" class="text-green-600 hover:bg-green-50 p-2 rounded-lg"><i class="fas fa-chevron-right"></i></button>
                    </div>

                    <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-gray-300 mb-2">
                        <div>DOM</div>
                        <div>SEG</div>
                        <div>TER</div>
                        <div>QUA</div>
                        <div>QUI</div>
                        <div>SEX</div>
                        <div>SAB</div>
                    </div>
                    <div id="calendarDays" class="grid grid-cols-7 gap-1 text-center text-sm"></div>
                </div>

                <!-- Seletor de Mês/Ano (Modal interno do calendário) -->
                <div id="monthYearSelector" class="month-year-selector"></div>
            </div>

            <!-- Filtro Ativo -->
            <div id="activeFilter" class="hidden mt-4 bg-green-50 p-4 rounded-2xl border border-green-100 animate-fade-in">
                <div class="flex justify-between items-center text-sm text-green-700 font-medium">
                    <span><i class="fas fa-calendar-day mr-2"></i> <span id="filterDate"></span></span>
                    <button id="clearFilter" class="hover:text-red-500 transition"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script da Engine -->
    <script src="js/convencional.js"></script>
</body>

</html>