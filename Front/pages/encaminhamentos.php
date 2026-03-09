<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php"); // Se não estiver logado, volta pro login
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encaminhamentos - CDI</title>

    <!-- CSS e Bibliotecas -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- 1. Estilos Globais -->
    <link rel="stylesheet" href="../css/global.css">
    <script src="../js/global.js"></script>

    <!-- 2. Scripts Específicos (Com DEFER para carregar após o HTML) -->
    <script src="../js/encaminhamentos.js" defer></script>

    <style>
        /* Ajuste para o efeito de vidro no cabeçalho */
        .glass-header {
            background-color: rgba(245, 240, 229, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .dark-mode .glass-header {
            background-color: rgba(17, 24, 39, 0.9);
        }
    </style>
</head>

<body class="bg-main min-h-screen flex flex-col md:flex-row">

    <!-- INCLUSÃO DA SIDEBAR CENTRALIZADA -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="flex-1 p-6 md:p-10 flex flex-col min-w-0">

        <!-- Cabeçalho Superior -->
        <header class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Encaminhamentos</h1>
                <p class="text-muted">Gestão de encaminhamentos para especialidades e serviços externos.</p>
            </div>
            <button id="btn-novo-encaminhamento" class="bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:opacity-90 transition transform hover:-translate-y-1 flex items-center justify-center" style="background-color: var(--primary-color);">
                <i class="fas fa-plus mr-2"></i> Novo Registro
            </button>
        </header>

        <!-- Filtros Rápidos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Filtrar Status</label>
                <select class="w-full mt-1 focus:outline-none bg-transparent text-gray-700 font-medium">
                    <option>Todos os Status</option>
                    <option>Pendente</option>
                    <option>Concluído</option>
                    <option>Cancelado</option>
                </select>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm md:col-span-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Buscar Paciente</label>
                <div class="flex items-center">
                    <i class="fas fa-search text-gray-300 mr-2"></i>
                    <input type="text" placeholder="Digite o nome do idoso..." class="w-full mt-1 focus:outline-none bg-transparent text-gray-700">
                </div>
            </div>
        </div>

        <!-- Lista de Encaminhamentos (Cards injetados pelo JS) -->
        <div id="lista-encaminhamentos" class="grid grid-cols-1 gap-4 pb-10">
            <!-- Loader ou Estado Vazio Inicial -->
            <div class="p-20 text-center text-gray-400 animate-pulse">
                <i class="fas fa-spinner fa-spin text-3xl mb-4"></i>
                <p>Carregando registros...</p>
            </div>
        </div>
    </main>

    <!-- MODAL DE NOVO ENCAMINHAMENTO -->
    <div id="modal-encaminhamento" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-fade-in">
            <div class="p-6 border-b flex justify-between items-center bg-gray-50/50">
                <h2 class="text-xl font-bold text-gray-800">Novo Registro</h2>
                <button onclick="EncaminhamentoApp.toggleModal(false)" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition text-gray-400">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="form-encaminhamento" class="p-8 space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Paciente</label>
                    <input type="text" name="paciente" required placeholder="Nome completo do idoso"
                        class="w-full border border-gray-200 p-4 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Data Prevista</label>
                        <input type="date" name="data" required
                            class="w-full border border-gray-200 p-4 rounded-2xl outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Urgência</label>
                        <select name="urgencia" class="w-full border border-gray-200 p-4 rounded-2xl outline-none focus:border-blue-500 transition bg-white">
                            <option value="Normal">Normal</option>
                            <option value="Alta">Alta</option>
                            <option value="Urgente">Urgente</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Destino / Especialidade</label>
                    <input type="text" name="destino" required placeholder="Ex: Hospital Municipal, CRAS, etc."
                        class="w-full border border-gray-200 p-4 rounded-2xl outline-none focus:border-blue-500 transition">
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="button" onclick="EncaminhamentoApp.toggleModal(false)"
                        class="flex-1 py-4 text-gray-500 font-bold hover:bg-gray-50 rounded-2xl transition">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-lg hover:opacity-90 transition" style="background-color: var(--primary-color);">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>