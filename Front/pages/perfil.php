<?php include '../includes/header.php'; ?>
    <title>Prontuário Digital - CDI</title>
    <style>
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 2rem;
            bottom: -1rem;
            width: 2px;
            background: var(--gray-200);
        }
        .timeline-item:last-child::before { display: none; }
    </style>
</head>

<body class="bg-main min-h-screen flex flex-col md:flex-row">
    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 p-4 md:p-10 min-w-0 overflow-y-auto">
        <div id="perfil-container" class="max-w-5xl mx-auto opacity-0 transition-opacity duration-500">
            <!-- Os dados serão injetados pelo JS -->
            <div class="flex justify-center p-20">
                <i class="fas fa-spinner fa-spin text-4xl text-primary"></i>
            </div>
        </div>
    </main>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const pacienteId = urlParams.get('id');

        async function carregarPerfil() {
            if (!pacienteId) {
                alert("Paciente não identificado.");
                return;
            }

            try {
                const res = await fetch(`../api/get_perfil_idoso.php?id=${pacienteId}`);
                const result = await res.json();

                if (result.status === 'sucesso') {
                    renderizarPerfil(result);
                } else {
                    document.getElementById('perfil-container').innerHTML = `<div class='text-center p-20'>${result.mensagem}</div>`;
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderizarPerfil(data) {
            const p = data.dados;
            const container = document.getElementById('perfil-container');
            
            container.innerHTML = `
                <!-- Cabeçalho do Perfil -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden mb-8 animate-fade-in">
                    <div class="h-32 bg-primary" style="background-color: var(--primary-color); opacity: 0.8;"></div>
                    <div class="px-8 pb-8 -mt-12">
                        <div class="flex flex-col md:flex-row items-end gap-6 mb-6">
                            <div class="w-32 h-32 rounded-3xl bg-white p-2 shadow-lg">
                                <div class="w-full h-full rounded-2xl bg-gray-100 flex items-center justify-center text-4xl text-gray-400">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="flex-1 pb-2">
                                <h1 class="text-3xl font-bold text-gray-800">${p.nome}</h1>
                                <p class="text-gray-500 font-medium">Matrícula: ${p.matricula || '---'}</p>
                            </div>
                            <div class="pb-2 flex gap-3">
                                <button id="btn-imprimir" onclick="window.print()" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-600 font-bold text-sm hover:bg-gray-200 transition flex items-center gap-2">
                                    <i class="fas fa-file-pdf"></i> PDF / Imprimir
                                </button>
                                <span class="px-4 py-2 rounded-full cdi-bg-success-light cdi-text-success font-bold text-sm flex items-center">
                                    <i class="fas fa-circle mr-2 text-[10px]"></i>Status: ${p.status.toUpperCase()}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 border-t border-gray-100 pt-8">
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">NIS</span>
                                <span class="font-bold text-gray-700">${p.nis || '---'}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">Data de Nascimento</span>
                                <span class="font-bold text-gray-700">${CDIUtils.formatarDataBR(p.data_nascimento)}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">Sexo</span>
                                <span class="font-bold text-gray-700 capitalize">${p.sexo || '---'}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">Cor/Raça</span>
                                <span class="font-bold text-gray-700 capitalize">${p.cor_raca || '---'}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Coluna Esquerda: Linha do Tempo -->
                    <div class="lg:col-span-2 space-y-10">
                        
                        <!-- 1. Linha do Tempo de Cuidados -->
                        <section>
                            <h2 class="text-xl font-bold text-gray-800 flex items-center mb-6">
                                <i class="fas fa-history mr-3 cdi-text-primary"></i>
                                Evoluções e Atividades
                            </h2>
                            <div class="space-y-4">
                                ${data.atividades.length > 0 ? data.atividades.map(at => `
                                    <div class="relative pl-10 timeline-item">
                                        <div class="absolute left-0 w-6 h-6 rounded-full bg-white border-4 cdi-text-primary flex items-center justify-center z-10" style="border-color: var(--primary-color);"></div>
                                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="text-xs font-bold cdi-text-primary uppercase">${at.tipo}</span>
                                                <span class="text-xs text-gray-400">${new Date(at.data_postagem).toLocaleString('pt-BR')}</span>
                                            </div>
                                            <p class="text-gray-700 leading-relaxed">${at.descricao}</p>
                                            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-[10px] text-gray-400 uppercase font-bold tracking-tighter">
                                                <i class="fas fa-user-nurse mr-2"></i> Registrado por: ${at.funcionario || 'Sistema'}
                                            </div>
                                        </div>
                                    </div>
                                `).join('') : '<div class="bg-white p-10 rounded-2xl text-center text-gray-400 border border-dashed border-gray-200">Nenhum registro histórico encontrado.</div>'}
                            </div>
                        </section>

                        <!-- 2. Histórico de Encaminhamentos -->
                        <section>
                            <h2 class="text-xl font-bold text-gray-800 flex items-center mb-6">
                                <i class="fas fa-share-square mr-3 text-purple-500"></i>
                                Encaminhamentos Externos
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                ${data.encaminhamentos.length > 0 ? data.encaminhamentos.map(en => `
                                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                                        <div class="flex justify-between items-start mb-3">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase ${en.status === 'Concluído' ? 'cdi-bg-success-light cdi-text-success' : 'cdi-bg-warning-light cdi-text-warning'}">
                                                ${en.status}
                                            </span>
                                            <span class="text-[10px] font-bold text-gray-400">${new Date(en.data).toLocaleDateString('pt-BR')}</span>
                                        </div>
                                        <h4 class="font-bold text-gray-800 mb-1">${en.destino}</h4>
                                        <p class="text-xs text-gray-500">Urgência: <span class="font-bold ${en.urgencia === 'Urgente' ? 'cdi-text-danger' : 'text-gray-700'}">${en.urgencia}</span></p>
                                    </div>
                                `).join('') : '<div class="col-span-2 bg-gray-50 p-6 rounded-2xl text-center text-gray-400 text-sm">Sem encaminhamentos registrados.</div>'}
                            </div>
                        </section>
                    </div>

                    <!-- Coluna Direita: Resumo de Saúde e Presença -->
                    <div class="space-y-8">
                        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-calendar-check mr-2 cdi-text-success"></i> Frequência Recente
                            </h3>
                            <div class="space-y-2">
                                ${(() => {
                                    const dataCadastro = new Date(p.cadastrado_em).toISOString().split('T')[0];
                                    const ultimos7Dias = [];
                                    for (let i = 0; i < 7; i++) {
                                        const d = new Date();
                                        d.setDate(d.getDate() - i);
                                        const dataISO = d.toISOString().split('T')[0];
                                        const diaSemana = d.getDay(); 
                                        ultimos7Dias.push({ dataISO, diaSemana });
                                    }

                                    return ultimos7Dias.map(diaObj => {
                                        const registro = data.presencas.find(pr => pr.data_presenca === diaObj.dataISO);
                                        let statusTxt = "";
                                        let statusCor = "";

                                        if (diaObj.dataISO < dataCadastro) {
                                            statusTxt = "Pré-admissão";
                                            statusCor = "cdi-text-muted italic";
                                        } 
                                        else if (registro) {
                                            statusTxt = registro.status == 1 ? "Presente" : "Faltou";
                                            statusCor = registro.status == 1 ? "cdi-text-success" : "cdi-text-danger";
                                        } 
                                        else {
                                            if (diaObj.diaSemana === 0 || diaObj.diaSemana === 6) {
                                                statusTxt = "Fim de Semana";
                                                statusCor = "cdi-text-muted";
                                            } else {
                                                statusTxt = "Não Lançado";
                                                statusCor = "cdi-text-warning";
                                            }
                                        }

                                        return `
                                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2">
                                                <div class="flex flex-col">
                                                    <span class="text-gray-600 font-medium">${CDIUtils.formatarDataBR(diaObj.dataISO)}</span>
                                                    <span class="text-[9px] uppercase text-gray-400">${["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"][diaObj.diaSemana]}</span>
                                                </div>
                                                <span class="font-bold ${statusCor}">${statusTxt}</span>
                                            </div>
                                        `;
                                    }).join('');
                                })()}
                            </div>
                            <p class="text-[10px] text-gray-400 mt-4 uppercase text-center font-bold tracking-widest">Últimos 7 dias corridos</p>
                        </div>

                        <div class="bg-blue-600 rounded-3xl p-6 text-white shadow-lg cdi-bg-primary">
                            <h3 class="font-bold mb-2">Observações Críticas</h3>
                            <p class="text-blue-100 text-sm leading-relaxed">
                                Nenhuma observação crítica registrada para este paciente no momento.
                            </p>
                        </div>
                    </div>
                </div>
            `;
            container.classList.remove('opacity-0');
        }

        document.addEventListener('DOMContentLoaded', carregarPerfil);
    </script>
<?php include '../includes/footer.php'; ?>
