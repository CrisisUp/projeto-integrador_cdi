<?php include '../includes/header.php'; ?>
    <title>Menu de Opções - CDI</title>
    <link rel="stylesheet" href="../css/navegacao.css">
</head>

<body>
    <div class="min-h-screen flex flex-col">
        <!-- Cabeçalho com boas-vindas e botão de logout -->
        <header class="sticky top-0 z-10 bg-opacity-90 shadow-sm" style="background-color: rgba(245, 240, 229, 0.95);">
            <div class="p-4 flex justify-between items-center">
                <div class="flex items-center">
                    <h2 class="cdi-text-lg font-medium">Bem Vindo <span class="font-bold"><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span></h2>
                </div>
                <a href="../logout.php" class="logout-btn flex items-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sair</span>
                </a>
            </div>
        </header>

        <!-- Conteúdo Principal -->
        <main class="flex-1 p-4 md:p-6 lg:p-8">
            <div class="max-w-6xl mx-auto">
                <h1 class="cdi-text-2xl font-bold mb-6">Painel Administrativo</h1>

                <!-- DASHBOARD: INDICADORES RÁPIDOS -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10 animate-fade-in">
                    <!-- Presentes Hoje -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl cdi-bg-success-light cdi-text-success flex items-center justify-center cdi-text-xl">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <span id="stat-presentes" class="block cdi-text-2xl font-bold text-gray-800">0</span>
                            <p class="cdi-text-xs font-bold text-gray-400 uppercase tracking-widest">Presentes Hoje</p>
                        </div>
                    </div>

                    <!-- Evoluções 24h -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl cdi-bg-primary-light cdi-text-primary flex items-center justify-center cdi-text-xl">
                            <i class="fas fa-notes-medical"></i>
                        </div>
                        <div>
                            <span id="stat-evolucoes" class="block cdi-text-2xl font-bold text-gray-800">0</span>
                            <p class="cdi-text-xs font-bold text-gray-400 uppercase tracking-widest">Evoluções (24h)</p>
                        </div>
                    </div>

                    <!-- Encaminhamentos Urgentes -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl cdi-bg-danger-light cdi-text-danger flex items-center justify-center cdi-text-xl">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <span id="stat-urgentes" class="block cdi-text-2xl font-bold text-gray-800">0</span>
                            <p class="cdi-text-xs font-bold text-gray-400 uppercase tracking-widest">Urgentes Pendentes</p>
                        </div>
                    </div>

                    <!-- Total Ativos -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl cdi-bg-warning-light cdi-text-warning flex items-center justify-center cdi-text-xl">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <span id="stat-total" class="block cdi-text-2xl font-bold text-gray-800">0</span>
                            <p class="cdi-text-xs font-bold text-gray-400 uppercase tracking-widest">Total de Ativos</p>
                        </div>
                    </div>
                </div>

                <h2 class="cdi-text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-6">Menu de Ações</h2>

                <!-- Matriz de Opções -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- 1. Cadastro -->
                    <div class="opacity-0 animate-fade-in">
                        <div class="option-card" data-option="cadastro">
                            <i class="fas fa-user-plus option-icon"></i>
                            <h3 class="cdi-text-lg font-bold mb-2">Cadastro</h3>
                            <p class="text-gray-600">Gerenciar cadastros de pacientes e profissionais</p>
                        </div>
                    </div>

                    <!-- 2. Atividade Convencional -->
                    <div class="opacity-0 animate-fade-in delay-100">
                        <div class="option-card" data-option="atividade-convencional">
                            <i class="fas fa-clipboard-list option-icon"></i>
                            <h3 class="cdi-text-lg font-bold mb-2">Atividade Convencional</h3>
                            <p class="text-gray-600">Registrar e gerenciar atividades padrão</p>
                        </div>
                    </div>

                    <!-- 3. Atividade Enfermagem -->
                    <div class="opacity-0 animate-fade-in delay-200">
                        <div class="option-card" data-option="atividade-enfermagem">
                            <i class="fas fa-heartbeat option-icon"></i>
                            <h3 class="cdi-text-lg font-bold mb-2">Atividade Enfermagem</h3>
                            <p class="text-gray-600">Registrar procedimentos e cuidados de enfermagem</p>
                        </div>
                    </div>

                    <!-- 4. Frequência -->
                    <div class="opacity-0 animate-fade-in delay-300">
                        <div class="option-card" data-option="frequencia">
                            <i class="fas fa-calendar-check option-icon"></i>
                            <h3 class="cdi-text-lg font-bold mb-2">Frequência</h3>
                            <p class="text-gray-600">Controle de presença e frequência</p>
                        </div>
                    </div>

                    <!-- 5. Encaminhamentos -->
                    <div class="opacity-0 animate-fade-in delay-400">
                        <div class="option-card" data-option="encaminhamentos">
                            <i class="fas fa-share-square option-icon"></i>
                            <h3 class="cdi-text-lg font-bold mb-2">Encaminhamentos</h3>
                            <p class="text-gray-600">Gerenciar encaminhamentos para especialidades</p>
                        </div>
                    </div>

                    <!-- 6. Configurações -->
                    <div class="opacity-0 animate-fade-in delay-500">
                        <div class="option-card" data-option="configuracoes">
                            <i class="fas fa-cog option-icon"></i>
                            <h3 class="cdi-text-lg font-bold mb-2">Configurações</h3>
                            <p class="text-gray-600">Ajustar preferências e configurações do sistema</p>
                        </div>
                    </div>
                </div>

                <!-- Botão de Confirmação -->
                <div class="mt-8 text-center opacity-0 animate-fade-in delay-600">
                    <button id="confirmButton" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-full transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Acessar
                    </button>
                </div>
            </div>
        </main>

    </div>
    <script src="../js/navegacao.js"></script>
<?php include '../includes/footer.php'; ?>