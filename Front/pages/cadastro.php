<?php include '../includes/header.php'; ?>
<title>Cadastro - CDI</title>
<link rel="stylesheet" href="../css/cadastro.css">
</head>

<body class="bg-main min-h-screen flex flex-col md:flex-row">
    <!-- INCLUSÃO DA SIDEBAR CENTRALIZADA -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Conteúdo Principal -->
    <div class="flex-1 min-w-0 p-4 md:p-8">
        <header class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Gerenciar Idosos</h1>
                <p class="cdi-text-muted">Cadastre novos idosos ou edite informações existentes.</p>
            </div>
            
            <!-- BUSCA PARA EDIÇÃO -->
            <div class="bg-white p-3 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-3 min-w-[300px]">
                <i class="fas fa-search text-gray-400 ml-2"></i>
                <select id="select-editar" class="w-full bg-transparent outline-none text-sm font-medium text-gray-700">
                    <option value="">Novo Cadastro...</option>
                    <!-- Populado via JS -->
                </select>
            </div>
        </header>

        <form id="cadastroForm" class="max-w-6xl mx-auto">
            <!-- Campo oculto para o ID (Necessário para saber se é edição ou novo) -->
            <input type="hidden" id="paciente_id" name="id">

            <!-- Seção 1: Dados Gerais -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
                <div id="form-header-color" class="cdi-bg-primary text-white px-6 py-4 transition-colors">
                    <h2 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-id-card mr-3"></i><span id="form-title">Novo Cadastro</span>
                    </h2>
                </div>

                <!-- GRID RESPONSIVA: 1 col no celular, 2 no tablet, 4 no desktop -->
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-600 uppercase mb-1 required">Nº Matrícula</label>
                        <input type="text" id="matricula" name="matricula" class="form-input-style">
                    </div>

                    <div class="flex flex-col md:col-span-2 lg:col-span-3">
                        <label class="text-xs font-bold text-gray-600 uppercase mb-1 required">Nome do Usuário</label>
                        <input type="text" id="nome" name="nome" class="form-input-style">
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-600 uppercase mb-1 required">Sexo</label>
                        <select id="sexo" name="sexo" class="form-input-style">
                            <option value="">Selecione</option>
                            <option value="masculino">Masculino</option>
                            <option value="feminino">Feminino</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-600 uppercase mb-1 required">Cor/Raça</label>
                        <select id="cor_raca" name="cor_raca" class="form-input-style">
                            <option value="">Selecione</option>
                            <option value="branca">Branca</option>
                            <option value="preta">Preta</option>
                            <option value="parda">Parda</option>
                            <option value="amarela">Amarela</option>
                            <option value="indigena">Indígena</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-600 uppercase mb-1 required">NIS</label>
                        <input
                            type="text"
                            id="nis"
                            name="nis"
                            class="form-input-style"
                            placeholder="000.00000.00-0"
                            maxlength="14"
                            autocomplete="off">
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-600 uppercase mb-1 required">Data de Nascimento</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" class="form-input-style" 
                            max="<?php echo date('Y-m-d'); ?>" onchange="calcularIdade()">
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-600 uppercase mb-1">Idade</label>
                        <input type="text" id="idade" name="idade" class="form-input-style bg-gray-50 font-bold" readonly>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-600 uppercase mb-1">Status</label>
                        <select id="status" name="status" class="form-input-style font-bold">
                            <option value="ativo">Ativo</option>
                            <option value="desligado">Desligado</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Seção 2: Dieta e Benefícios (Lado a lado no monitor, empilhados no celular) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

                <!-- Card Dieta -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="cdi-bg-success text-white px-6 py-3 font-semibold flex items-center">
                        <i class="fas fa-utensils mr-2"></i>Dieta
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-gray-600 uppercase mb-1">Lanche Tipo 2</label>
                            <select name="lanche_tipo_2" class="form-input-style">
                                <option>Selecione</option>
                                <option>Sim</option>
                                <option>Não</option>
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-gray-600 uppercase mb-1">Dieta Especial</label>
                            <select name="dieta_especial" id="dieta_especial" class="form-input-style">
                                <option>Selecione</option>
                                <option value="sim">Sim</option>
                                <option value="nao">Não</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Card Benefícios -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="cdi-bg-primary text-white px-6 py-3 font-semibold flex items-center">
                        <i class="fas fa-hand-holding-heart mr-2 required"></i> Benefícios
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input
                                type="checkbox"
                                id="nao_recebe"
                                name="beneficios[]"
                                value="Não recebe"
                                class="h-4 w-4 cdi-text-primary">
                            <span>Não recebe</span>
                        </label>
                        <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input
                                type="checkbox"
                                name="beneficios[]" value="Bolsa Família" class="h-4 w-4 cdi-text-primary">
                            <span>Bolsa Família</span>
                        </label>
                        <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input
                                type="checkbox"
                                name="beneficios[]"
                                value="BPC - Idoso"
                                class="h-4 w-4 cdi-text-primary">
                            <span>BPC - Idoso</span>
                        </label>
                        <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input
                                type="checkbox"
                                name="beneficios[]"
                                value="Aposentadoria"
                                class="h-4 w-4 cdi-text-primary">
                            <span>Aposentadoria</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação (Ajustados para celular) -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 pb-12">
                <button type="reset" class="px-6 py-3 cdi-bg-muted cdi-text-gray font-bold rounded-lg hover:opacity-80 transition duration-200">Limpar</button>
                <button type="submit" class="px-10 py-3 cdi-bg-primary text-white font-bold rounded-lg hover:opacity-90 shadow-md transition duration-200">Salvar Cadastro</button>
            </div>
        </form>
    </div>

    <script src="../js/cadastro.js"></script>
    <script>
        function calcularIdade() {
            const dataNasc = document.getElementById('data_nascimento').value;
            if (!dataNasc) return;

            const hoje = new Date();
            const nasc = new Date(dataNasc);
            let idade = hoje.getFullYear() - nasc.getFullYear();
            const m = hoje.getMonth() - nasc.getMonth();
            if (m < 0 || (m === 0 && hoje.getDate() < nasc.getDate())) {
                idade--;
            }
            document.getElementById('idade').value = idade >= 0 ? idade + " anos" : "Data inválida";
        }
    </script>
<?php include '../includes/footer.php'; ?>