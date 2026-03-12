<?php include '../includes/header.php'; ?>
<title>Frequência Diária - CDI</title>
<link rel="stylesheet" href="../css/presenca.css">
</head>

<body class="bg-main min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Única -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Área de Conteúdo Principal -->
    <div class="flex-1 min-w-0 flex flex-col">

        <!-- Cabeçalho de Identificação -->
        <header class="sticky top-0 z-30 cdi-border-b glass-header">
            <div class="p-4 flex justify-between items-center">
                <h1 class="cdi-text-xl font-bold cdi-text-gray">Frequência Diária</h1>
                <div class="flex items-center gap-3">
                    <span class="cdi-text-sm font-medium cdi-text-gray hidden md:block">Unidade Central</span>
                    <div class="w-8 h-8 rounded-full cdi-bg-primary flex items-center justify-center cdi-text-white cdi-text-xs">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-8 overflow-y-auto">
            <div class="max-w-[98%] mx-auto">

                <!-- Título e Seletor -->
                <div class="text-center mb-10">
                    <h2 class="cdi-text-3xl font-bold cdi-text-gray mb-2">Controle de Presença Mensal</h2>
                    <p class="cdi-text-muted mb-6">Selecione o mês desejado e clique nas células para marcar a frequência dos idosos.</p>

                    <div class="inline-flex items-center cdi-bg-white rounded-2xl shadow-sm cdi-border overflow-hidden">
                        <button id="prev-month" class="p-4 cdi-hover-muted cdi-text-muted transition">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div id="current-month" class="px-10 py-4 cdi-text-lg font-bold border-x cdi-border-light min-w-[200px]">
                            <!-- JS -->
                        </div>
                        <button id="next-month" class="p-4 cdi-hover-muted cdi-text-muted transition">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Tabela de Presença -->
                <div class="cdi-bg-white rounded-lg shadow-card cdi-border overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead id="grid-header" class="cdi-bg-muted">
                                <!-- JS -->
                            </thead>
                            <tbody id="grid">
                                <!-- JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 flex gap-6 justify-start cdi-text-xs cdi-text-muted">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded cdi-bg-success-light border cdi-border-success"></span> Presente (1)
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded cdi-bg-muted opacity-50 border"></span> Fim de Semana
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="mt-12 flex justify-center items-center pb-20">
                    <button id="reset-btn" class="w-full sm:w-auto px-10 py-4 cdi-bg-muted cdi-text-white font-bold rounded-2xl cdi-hover-muted shadow-lg transition-all transform hover:-translate-y-1">
                        <i class="fas fa-eye-slash mr-2"></i> Nenhum Nome Oculto
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL DE GESTÃO DE OCULTOS -->
    <div id="modal-ocultos" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4" style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
        <div class="cdi-bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-fade-in">
            <div class="p-6 cdi-border-b flex justify-between items-center cdi-bg-muted">
                <h2 class="cdi-text-xl font-bold cdi-text-gray">Gerenciar Ocultos</h2>
                <button onclick="document.getElementById('modal-ocultos').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full cdi-hover-muted transition cdi-text-muted">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="lista-pacientes-ocultos" class="p-6 max-h-[400px] overflow-y-auto space-y-3">
                <!-- Populado via JS -->
            </div>
            <div class="p-4 cdi-bg-muted cdi-border-t text-center">
                <button onclick="PresencaApp.restaurarTodos()" class="cdi-text-primary font-bold hover:underline cdi-text-sm">
                    <i class="fas fa-undo mr-1"></i> Restaurar Todos
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts de Funcionalidade -->
    <script src="../js/presenca.js"></script>
    <?php include '../includes/footer.php'; ?>