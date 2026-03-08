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
    <!-- Usaremos o estilo de navegação para a sidebar e cards -->
    <link rel="stylesheet" href="css/navegacao.css">

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

<body class="bg-main min-h-screen flex flex-col md:flex-row">

    <!-- INCLUSÃO DA SIDEBAR CENTRALIZADA -->
    <?php include 'sidebar.php'; ?>

    <!-- Conteúdo Principal -->
    <div class="flex-1 min-w-0 p-4 md:p-8">
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
                            <div onclick="setThemeColor('#1d9bf0')" class="color-dot bg-blue-500" title="Azul Padrão"></div>
                            <div onclick="setThemeColor('#10b981')" class="color-dot bg-green-500" title="Verde"></div>
                            <div onclick="setThemeColor('#f59e0b')" class="color-dot bg-yellow-500" title="Âmbar"></div>
                            <div onclick="setThemeColor('#ef4444')" class="color-dot bg-red-500" title="Vermelho"></div>
                            <div onclick="setThemeColor('#8b5cf6')" class="color-dot bg-purple-500" title="Roxo"></div>
                            <div onclick="setThemeColor('#374151')" class="color-dot bg-gray-700" title="Grafite"></div>
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
    </div>

    <script>
        const darkModeSwitch = document.getElementById('dark-mode-switch');

        // 1. Carregar preferências ao iniciar
        window.addEventListener('DOMContentLoaded', () => {
            const savedColor = localStorage.getItem('theme-color') || '#1d9bf0';
            setThemeColor(savedColor);

            const isDark = localStorage.getItem('dark-mode') === 'true';
            darkModeSwitch.checked = isDark;
            if (isDark) applyDarkMode(true);
        });

        // 2. Função Principal para mudar a cor
        function setThemeColor(color) {
            // Altera as variáveis no CSS
            document.documentElement.style.setProperty('--primary-color', color);
            document.documentElement.style.setProperty('--primary-light', color + '26');
            document.documentElement.style.setProperty('--primary-glow', `0 0 0 3px ${color}33`);

            // Salva a escolha
            localStorage.setItem('theme-color', color);

            // Executa a função de atualização visual (que estava faltando)
            updateColorDots(color);
        }

        // 3. Função para dar feedback visual nos círculos de cores
        function updateColorDots(activeColor) {
            document.querySelectorAll('.color-dot').forEach(dot => {
                // Remove a classe de destaque de todos
                dot.classList.remove('active', 'scale-110', 'border-gray-800');
                dot.style.transform = "scale(1)";

                // Adiciona destaque apenas no que combina com a cor ativa
                if (rgbToHex(dot.style.backgroundColor) === activeColor.toLowerCase()) {
                    dot.classList.add('active', 'scale-110');
                    dot.style.transform = "scale(1.2)";
                    dot.style.borderColor = "#333"; // Borda escura para indicar seleção
                }
            });
        }

        // 4. Lógica do Modo Escuro
        darkModeSwitch.addEventListener('change', (e) => {
            applyDarkMode(e.target.checked);
        });

        function applyDarkMode(enabled) {
            if (enabled) {
                document.documentElement.style.setProperty('--white', '#1f2937');
                document.documentElement.style.setProperty('--bg-main', '#111827');
                document.body.style.color = '#f9fafb';
                localStorage.setItem('dark-mode', 'true');
            } else {
                document.documentElement.style.setProperty('--white', '#ffffff');
                document.documentElement.style.setProperty('--bg-main', '#f5f0e5');
                document.body.style.color = '#1f2937';
                localStorage.setItem('dark-mode', 'false');
            }
        }

        // Função auxiliar para converter RGB (do navegador) para HEX (nosso padrão)
        function rgbToHex(rgb) {
            if (!rgb) return "";
            const vals = rgb.match(/\d+/g);
            if (!vals) return "";
            return "#" + vals.map(x => parseInt(x).toString(16).padStart(2, '0')).join('');
        }
    </script>
</body>

</html>