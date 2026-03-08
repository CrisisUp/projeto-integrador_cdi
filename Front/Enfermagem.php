<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atividade Enfermagem - CDI</title>

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- 1. Estilo Global -->
    <link rel="stylesheet" href="css/global.css">
    <script src="js/global.js"></script>

    <!-- 2. Estilo Específico -->
    <link rel="stylesheet" href="css/enfermagem.css">
</head>

<body class="bg-main min-h-screen flex flex-col md:flex-row">

    <!-- AQUI ENTRA A MÁGICA: Substituímos o código gigante por uma linha -->
    <?php include 'sidebar.php'; ?>

    <!-- Conteúdo Principal -->
    <div class="flex-1 min-w-0 flex">

        <!-- Coluna do Feed (Centro) -->
        <div class="flex-1 border-r border-gray-300">
            <!-- Cabeçalho -->
            <header class="sticky top-0 z-10 border-b border-gray-300 glass-header" style="background-color: rgba(245, 240, 229, 0.9); backdrop-filter: blur(8px);">
                <div class="p-4">
                    <h1 class="text-xl font-bold text-gray-800">Enfermagem</h1>
                </div>
                <div class="bg-white/50">
                    <div class="py-4 text-center font-bold border-b-4 border-blue-500 text-blue-600">
                        Evolução Diária e Atividades
                    </div>
                </div>
            </header>

            <!-- Formulário de Postagem (Evolução) -->
            <div class="border-b border-gray-300 p-4 bg-white/30">
                <div class="flex">
                    <div class="w-12 h-12 rounded-full bg-primary flex-shrink-0 flex items-center justify-center text-white" style="background-color: var(--primary-color);">
                        <i class="fas fa-user-nurse text-xl"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <textarea class="w-full bg-transparent text-lg outline-none resize-none placeholder-gray-500 border-b border-transparent focus:border-blue-500 transition" placeholder="Relate a evolução ou intercorrência do paciente..." rows="3"></textarea>
                        <div class="flex justify-between items-center mt-4">
                            <div class="flex space-x-4 text-blue-500">
                                <button title="Anexar Imagem"><i class="fas fa-image"></i></button>
                                <button title="Sinais Vitais"><i class="fas fa-heartbeat"></i></button>
                            </div>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-full transition shadow-md" style="background-color: var(--primary-color);">
                                Registrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feed de Postagens -->
            <div id="posts-container" class="divide-y divide-gray-200">
                <!-- Injetado pelo JS -->
            </div>
        </div>

        <!-- Barra Lateral Direita (Filtros e Calendário) -->
        <div class="w-80 p-4 hidden lg:block sticky top-0 h-screen overflow-y-auto bg-gray-50/50">
            <!-- Pesquisa -->
            <div class="relative mb-6">
                <input type="text" placeholder="Buscar evolução..." class="w-full bg-white border border-gray-200 rounded-full py-3 px-5 pl-12 outline-none focus:ring-2 focus:ring-blue-400 shadow-sm">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
            </div>

            <!-- Calendário -->
            <div class="mb-6 relative">
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Filtrar por Data</h2>
                <div id="calendar" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <button id="prevMonth" class="text-blue-500"><i class="fas fa-chevron-left"></i></button>
                        <h3 id="monthYear" class="font-bold text-gray-700"></h3>
                        <button id="nextMonth" class="text-blue-500"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-gray-400 mb-2">
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

                <!-- Seletor de Mês e Ano (Dropdown) -->
                <div id="monthYearSelector" class="month-year-selector">
                    <!-- Gerado pelo JS -->
                </div>
            </div>

            <div id="activeFilter" class="hidden bg-blue-50 p-4 rounded-xl border border-blue-100">
                <div class="flex justify-between items-center text-sm text-blue-700">
                    <span><i class="fas fa-filter mr-2"></i> <span id="filterDate" class="font-bold"></span></span>
                    <button id="clearFilter" class="hover:text-red-500"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/enfermagem.js"></script>
</body>

</html>