<?php include '../includes/header.php'; ?>
    <title>Configurações - CDI</title>
    <style>
        body {
            background-color: var(--bg-main);
            transition: background-color 0.3s ease;
        }

        .color-dot {
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 4px solid var(--white);
            box-shadow: 0 0 0 1px var(--gray-200);
            transition: transform 0.2s;
        }

        .color-dot:hover {
            transform: scale(1.15);
        }

        .color-dot.active {
            border-color: var(--gray-700);
        }
    </style>
</head>

<body class="bg-main min-h-screen flex flex-col md:flex-row overflow-x-hidden">

    <!-- INCLUSÃO DA SIDEBAR CENTRALIZADA -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Conteúdo Principal -->
    <main class="flex-1 min-w-0 p-4 md:p-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Configurações</h1>
            <p class="cdi-text-muted">Personalize a aparência e o comportamento do seu sistema.</p>
        </header>

        <div class="max-w-4xl mx-auto space-y-8">

            <!-- SEÇÃO: APARÊNCIA -->
            <section class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden animate-fade-in">
                <div class="cdi-bg-accent text-white px-6 py-4 flex items-center">
                    <i class="fas fa-palette mr-3 text-xl"></i>
                    <h2 class="text-lg font-semibold">Aparência e Temas</h2>
                </div>

                <div class="p-6 md:p-8 space-y-10">

                    <!-- Cores do Tema -->
                    <div>
                        <h3 class="text-sm font-bold cdi-text-gray uppercase tracking-wider mb-4">Cor Principal do Sistema</h3>
                        <div class="flex flex-wrap gap-6">
                            <!-- Forçamos o background-color exato para o JS reconhecer -->
                            <div onclick="setThemeColor('#1d9bf0')" class="color-dot" style="background-color: #1d9bf0;" title="Azul Padrão"></div>
                            <div onclick="setThemeColor('#10b981')" class="color-dot" style="background-color: #10b981;" title="Verde"></div>
                            <div onclick="setThemeColor('#f59e0b')" class="color-dot" style="background-color: #f59e0b;" title="Âmbar"></div>
                            <div onclick="setThemeColor('#ef4444')" class="color-dot" style="background-color: #ef4444;" title="Vermelho"></div>
                            <div onclick="setThemeColor('#8b5cf6')" class="color-dot" style="background-color: #8b5cf6;" title="Roxo"></div>
                            <div onclick="setThemeColor('#374151')" class="color-dot" style="background-color: #374151;" title="Grafite"></div>
                        </div>
                        <p class="mt-4 text-sm cdi-text-muted">Esta cor será aplicada em botões, links e destaques em todas as páginas.</p>
                    </div>

                    <hr class="border-gray-100">

                    <!-- Modo Escuro -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Modo Escuro</h3>
                            <p class="text-sm cdi-text-muted max-w-md">Inverte as cores de fundo e texto para proporcionar mais conforto visual em ambientes com pouca luz.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="dark-mode-switch" class="sr-only peer">
                            <div class="w-14 h-7 cdi-bg-muted peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:cdi-bg-accent"></div>
                        </label>
                    </div>

                </div>
            </section>

            <!-- SEÇÃO: SEGURANÇA (ALTERAÇÃO DE SENHA) -->
            <section class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden animate-fade-in">
                <div class="cdi-bg-dark text-white px-6 py-4 flex items-center">
                    <i class="fas fa-user-shield mr-3 text-xl"></i>
                    <h2 class="text-lg font-semibold">Segurança da Conta</h2>
                </div>
                <div class="p-6 md:p-8">
                    <h3 class="text-sm font-bold cdi-text-gray uppercase tracking-wider mb-6">Alterar Senha de Acesso</h3>
                    
                    <form id="formAlterarSenha" class="space-y-4 max-w-md">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Senha Atual</label>
                            <input type="password" id="senha_atual" name="senha_atual" class="w-full border border-gray-300 rounded-xl py-2 px-4 outline-none transition" required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nova Senha</label>
                                <input type="password" id="nova_senha" name="nova_senha" class="w-full border border-gray-300 rounded-xl py-2 px-4 outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nova Senha</label>
                                <input type="password" id="confirmacao" name="confirmacao" class="w-full border border-gray-300 rounded-xl py-2 px-4 outline-none transition" required>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="cdi-bg-accent cdi-hover-accent text-white font-bold py-2 px-6 rounded-xl transition shadow-md">
                                Atualizar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </section>

        </div>
    </main>

    <!-- Scripts de Funcionalidade -->
    <script src="../js/configuracoes.js"></script>
<?php include '../includes/footer.php'; ?>