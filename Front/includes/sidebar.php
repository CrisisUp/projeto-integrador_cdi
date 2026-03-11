<!-- sidebar.php -->
<aside class="w-full md:w-64 border-r border-gray-300 bg-white flex flex-col transition-all duration-300 md:h-screen sticky top-0 z-40 sidebar">
    <div class="p-6 flex items-center border-b border-gray-100">
        <div class="w-10 h-10 rounded-xl cdi-bg-primary flex items-center justify-center text-white shadow-lg">
            <i class="fas fa-heartbeat text-xl"></i>
        </div>
        <span class="ml-3 font-bold text-gray-800 tracking-tight">CDI Digital</span>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <a href="navegacao.php" class="flex items-center p-3 cdi-text-gray sidebar-item rounded-full mb-2 transition hover:bg-gray-100">
            <i class="fas fa-home mr-4 text-xl"></i><span class="text-xl">Início</span>
        </a>
        <a href="cadastro.php" class="flex items-center p-3 cdi-text-gray sidebar-item rounded-full mb-2 transition hover:bg-gray-100">
            <i class="fas fa-user-plus mr-4 text-xl"></i> <span class="text-xl">Cadastro</span>
        </a>
        <a href="presenca.php" class="flex items-center p-3 cdi-text-gray sidebar-item rounded-full mb-2 transition hover:bg-gray-100">
            <i class="fas fa-calendar-check mr-4 text-xl"></i><span class="text-xl">Frequência</span>
        </a>
        <a href="encaminhamentos.php" class="flex items-center p-3 cdi-text-gray sidebar-item rounded-full mb-2 transition hover:bg-gray-100">
            <i class="fas fa-file-export mr-4 text-xl"></i> <span class="text-xl">Encaminhamento</span>
        </a>

        <div class="pt-4 pb-2">
            <p class="text-[10px] font-bold text-gray-400 uppercase px-3 tracking-widest">Saúde</p>
        </div>

        <a href="enfermagem.php" class="flex items-center p-3 cdi-text-gray sidebar-item rounded-full mb-2 transition hover:bg-gray-100">
            <i class="fas fa-heartbeat mr-4 text-xl"></i><span class="text-xl">Enfermagem</span>
        </a>

        <a href="convencional.php" class="flex items-center p-3 cdi-text-gray sidebar-item rounded-full mb-2 transition hover:bg-gray-100">
            <i class="fas fa-heartbeat mr-4 text-xl"></i><span class="text-xl">Convencional</span>
        </a>

        <hr class="my-4 border-gray-100">

        <a href="configuracoes.php" class="flex items-center p-3 cdi-text-gray sidebar-item rounded-full mb-2 transition hover:bg-gray-100">
            <i class="fas fa-gear mr-4 text-xl"></i><span class="text-xl">Configurações</span>
        </a>
    </nav>

    <div class="p-4 border-t border-gray-100">
        <a href="../logout.php" class="flex items-center p-3 cdi-text-danger sidebar-item rounded-full whitespace-nowrap transition cdi-hover-danger-light font-medium md:mt-4">
            <i class="fas fa-sign-out-alt md:mr-4 text-xl"></i><span class="text-xl">Sair</span>
        </a>
    </div>
</aside>