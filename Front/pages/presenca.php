<?php include '../includes/header.php'; ?>
<title>Frequência Diária - CDI</title>
</head>

<body class="bg-main min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Única -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Área de Conteúdo Principal -->
    <div class="flex-1 min-w-0 flex flex-col">

        <!-- Cabeçalho de Identificação -->
        <header class="sticky top-0 z-30 border-b border-gray-300 glass-header">
            <div class="p-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Frequência Diária</h1>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-600 hidden md:block">Unidade Central</span>
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-xs" style="background-color: var(--primary-color);">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-8 overflow-y-auto">
            <div class="max-w-[98%] mx-auto">

                <!-- Título e Seletor -->
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Controle de Presença Mensal</h2>
                    <p class="text-muted mb-6">Selecione o mês desejado e clique nas células para marcar a frequência dos idosos.</p>

                    <div class="inline-flex items-center bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <button id="prev-month" class="p-4 hover:bg-gray-50 text-gray-400 transition">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div id="current-month" class="px-10 py-4 font-bold text-lg border-x border-gray-100 min-w-[200px]">
                            <!-- JS -->
                        </div>
                        <button id="next-month" class="p-4 hover:bg-gray-50 text-gray-400 transition">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Tabela de Presença -->
                <div class="bg-white rounded-lg shadow-card border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead id="grid-header" class="bg-gray-50">
                                <!-- JS -->
                            </thead>
                            <tbody id="grid">
                                <!-- JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="mt-12 flex flex-col sm:flex-row justify-center items-center gap-4 pb-20">
                    <button id="reset-btn" class="w-full sm:w-auto px-8 py-4 bg-red-500 text-white font-bold rounded-2xl hover:bg-red-600 shadow-lg transition-all transform hover:-translate-y-1">
                        <i class="fas fa-trash-can mr-2"></i> Limpar Mês Atual
                    </button>
                    <button id="add-person-btn" class="w-full sm:w-auto px-8 py-4 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 shadow-lg transition-all transform hover:-translate-y-1">
                        <i class="fas fa-user-plus mr-2"></i> Adicionar Pessoa
                    </button>
                </div>

            </div>
        </main>
    </div>

    <!-- Scripts de Funcionalidade -->
    <script src="../js/presenca.js"></script>
<?php include '../includes/footer.php'; ?>