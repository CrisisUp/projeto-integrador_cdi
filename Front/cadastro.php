<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - CDI</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- 1. Estilo Global (Variáveis e Base) -->
    <link rel="stylesheet" href="css/global.css">

    <!-- 2. Estilo Específico da Página -->
    <link rel="stylesheet" href="css/cadastro.css">
    <style>
        /* Estilos específicos para garantir o respiro da tabela */
        .form-grid table {
            border-spacing: 0 12px;
            border-collapse: separate;
        }

        .sidebar-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        body {
            background-color: #f5f0e5;
        }
    </style>
</head>

<body class="min-h-screen flex">
    <!-- Barra Lateral Esquerda -->
    <div class="w-64 border-r border-gray-300 flex flex-col h-screen sticky top-0 bg-white">
        <div class="p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center shadow-sm">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="font-bold text-gray-800 text-xl">Usuário</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4">
            <a href="navegacao.php" class="flex items-center p-3 text-gray-700 sidebar-item rounded-xl mb-2 transition">
                <i class="fas fa-home mr-4 text-xl"></i><span class="text-xl">Início</span>
            </a>
            <a href="convencional.php" class="flex items-center p-3 text-gray-700 sidebar-item rounded-xl mb-2 transition">
                <i class="fas fa-clipboard-list mr-4 text-xl"></i><span class="text-xl">Convencional</span>
            </a>
            <a href="enfermagem.php" class="flex items-center p-3 text-gray-700 sidebar-item rounded-xl mb-2 transition">
                <i class="fas fa-heartbeat mr-4 text-xl"></i><span class="text-xl">Enfermagem</span>
            </a>
            <a href="presenca.php" class="flex items-center p-3 text-gray-700 sidebar-item rounded-xl mb-2 transition">
                <i class="fas fa-calendar-check mr-4 text-xl"></i><span class="text-xl">Frequência</span>
            </a>
            <hr class="my-4 border-gray-200">
            <a href="login.php" class="flex items-center p-3 text-red-600 sidebar-item rounded-xl transition font-medium">
                <i class="fas fa-sign-out-alt mr-4 w-5"></i><span class="text-xl">Sair</span>
            </a>
        </nav>
    </div>

    <!-- Conteúdo Principal -->
    <div class="flex-1 min-w-0 p-8"> <!-- p-8 dá o respiro principal da página -->
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Cadastro</h1>
            <p class="text-gray-500">Insira as informações detalhadas do usuário abaixo.</p>
        </header>

        <form id="cadastroForm" class="max-w-6xl">
            <!-- Seção 1: Dados Gerais -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h2 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-id-card mr-3"></i> Dados Gerais
                    </h2>
                </div>

                <div class="p-8 form-grid"> <!-- p-8 aqui descola os campos da borda azul -->
                    <table class="w-full">
                        <tr>
                            <td class="w-1/6 text-xs font-bold text-gray-600 uppercase tracking-wider">Nº Matrícula</td>
                            <td class="w-2/6"><input type="text" id="matricula" name="matricula" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none"></td>
                            <td class="w-1/6 text-xs font-bold text-gray-600 uppercase tracking-wider pl-6">Nome do Usuário</td>
                            <td class="w-2/6" colspan="3"><input type="text" id="nome" name="nome" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none"></td>
                        </tr>
                        <tr>
                            <td class="text-xs font-bold text-gray-600 uppercase tracking-wider">Sexo</td>
                            <td>
                                <select id="sexo" name="sexo" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">Selecione</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="feminino">Feminino</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </td>
                            <td class="text-xs font-bold text-gray-600 uppercase tracking-wider pl-6">Cor/Raça</td>
                            <td>
                                <select id="cor_raca" name="cor_raca" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">Selecione</option>
                                    <option value="branca">Branca</option>
                                    <option value="preta">Preta</option>
                                    <option value="parda">Parda</option>
                                    <option value="amarela">Amarela</option>
                                    <option value="indigena">Indígena</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-xs font-bold text-gray-600 uppercase tracking-wider">NIS</td>
                            <td><input type="text" id="nis" name="nis" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none"></td>
                            <td class="text-xs font-bold text-gray-600 uppercase tracking-wider pl-6">Data de Nascimento</td>
                            <td><input type="date" id="data_nascimento" name="data_nascimento" class="w-full border border-gray-300 rounded-lg p-2 outline-none" onchange="calcularIdade()"></td>
                        </tr>
                        <tr>
                            <td class="text-xs font-bold text-gray-600 uppercase tracking-wider">Idade</td>
                            <td><input type="text" id="idade" name="idade" class="w-full border border-gray-300 rounded-lg p-2 bg-gray-50 font-bold" readonly></td>
                            <td class="text-xs font-bold text-gray-600 uppercase tracking-wider pl-6">Status</td>
                            <td>
                                <select id="status" name="status" class="w-full border border-gray-300 rounded-lg p-2 outline-none font-bold">
                                    <option value="ativo">Ativo</option>
                                    <option value="desligado">Desligado</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 mb-8"> <!-- gap-8 separa Dieta de Benefícios -->
                <!-- Seção Dieta -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="bg-green-600 text-white px-6 py-3 font-semibold">
                        <i class="fas fa-utensils mr-2"></i> Dieta
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Lanche Tipo 2</label>
                            <select class="w-full border border-gray-300 rounded-lg p-2 outline-none">
                                <option>Selecione</option>
                                <option>Sim</option>
                                <option>Não</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Dieta Especial</label>
                            <select id="dieta_especial" class="w-full border border-gray-300 rounded-lg p-2 outline-none">
                                <option>Selecione</option>
                                <option value="sim">Sim</option>
                                <option value="nao">Não</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Seção Benefícios -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="bg-purple-600 text-white px-6 py-3 font-semibold">
                        <i class="fas fa-hand-holding-heart mr-2"></i> Benefícios
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4">
                        <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" id="nao_recebe" name="beneficios" class="rounded text-blue-600"> <span>Não recebe</span>
                        </label>
                        <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="beneficios" class="rounded text-blue-600"> <span>Bolsa Família</span>
                        </label>
                        <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="beneficios" class="rounded text-blue-600"> <span>BPC - Idoso</span>
                        </label>
                        <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="beneficios" class="rounded text-blue-600"> <span>Aposentadoria</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end space-x-4 pb-12">
                <button type="reset" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">Limpar</button>
                <button type="submit" class="px-10 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md transition">Salvar Cadastro</button>
            </div>
        </form>
    </div>

    <script src="js/cadastro.js"></script>
</body>

</html>