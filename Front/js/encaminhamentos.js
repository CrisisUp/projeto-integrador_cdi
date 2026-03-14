/**
 * ARQUIVO: encaminhamentos.js - Integrado ao Design System CDI
 */
const EncaminhamentoApp = {
    dados: [],
    pacientes: [],

    async init() {
        await this.carregarPacientes();
        await this.carregarEncaminhamentos();
        this.bindEvents();
    },

    async carregarPacientes() {
        try {
            const res = await fetch("../api/get_cadastrados.php");
            this.pacientes = await res.json();
            const select = document.getElementById("select-pacientes");
            if (select) {
                select.innerHTML = '<option value="">Selecione o Idoso...</option>';
                this.pacientes.forEach(p => {
                    const opt = document.createElement("option");
                    opt.value = p.id;
                    const matriculaStr = p.matricula ? `[${p.matricula}]` : "[S/ MATRÍCULA]";
                    opt.textContent = `${matriculaStr} - ${p.nome}`;
                    select.appendChild(opt);
                });
            }
        } catch (e) { console.error(e); }
    },

    async carregarEncaminhamentos() {
        const busca = document.getElementById("busca-idoso")?.value || "";
        const status = document.getElementById("filtro-status")?.value || "";
        const container = document.getElementById("encaminhamentos-container");

        const url = `../api/get_encaminhamentos.php?busca=${encodeURIComponent(busca)}&status=${encodeURIComponent(status)}`;

        try {
            const res = await fetch(url);
            this.dados = await res.json();
            this.render();
        } catch (e) {
            CDIUtils.showToast("Erro ao carregar dados", "danger");
        }
    },

    toggleModal(show) {
        const modal = document.getElementById("modal-encaminhamento");
        if (!modal) return;
        if (show) {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        } else {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
            document.getElementById("formEncaminhamento")?.reset();
        }
    },

    render() {
        const container = document.getElementById("encaminhamentos-container");
        if (!container) return;

        if (this.dados.length === 0) {
            container.innerHTML = `
                <div class="col-span-full py-20 text-center">
                    <i class="fas fa-folder-open text-gray-200 cdi-text-5xl mb-4"></i>
                    <p class="text-gray-400">Nenhum encaminhamento encontrado.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = "";
        this.dados.forEach(item => {
            const card = document.createElement("div");
            card.className = "bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition-all animate-fade-in";
            
            const isConcluido = item.status === 'Concluído';
            const corStatus = isConcluido ? 'cdi-bg-success-light cdi-text-success' : 'cdi-bg-warning-light cdi-text-warning';
            const corUrgencia = item.urgencia === 'Urgente' ? 'cdi-text-danger font-bold' : 'text-gray-700 font-medium';
            const autor = item.funcionario_nome || 'Sistema';

            card.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full ${isConcluido ? 'cdi-bg-success-light cdi-text-success' : 'cdi-bg-primary-light cdi-text-primary'} flex items-center justify-center cdi-text-xl">
                            <i class="fas ${isConcluido ? 'fa-check-circle' : 'fa-file-medical'}"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 cdi-text-lg ${isConcluido ? 'line-through opacity-50' : ''}">${CDIUtils.escapeHTML(item.paciente_nome)}</h3>
                            <p class="cdi-text-xs cdi-text-primary font-bold uppercase tracking-tighter mb-1">Matrícula: ${CDIUtils.escapeHTML(item.matricula || '---')}</p>
                            <p class="cdi-text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i> ${CDIUtils.escapeHTML(item.destino)}</p>
                            <p class="cdi-text-xs text-gray-400 mt-1 uppercase">Registrado por: ${CDIUtils.escapeHTML(autor)}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 border-t border-gray-50 pt-4 mt-4">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Data</span>
                        <span class="text-gray-700 font-medium">${new Date(item.data).toLocaleDateString('pt-BR')}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Urgência</span>
                        <span class="${corUrgencia}">${CDIUtils.escapeHTML(item.urgencia)}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-6">
                    <div class="px-4 py-1 rounded-full cdi-text-xs font-bold ${corStatus}">
                        ${CDIUtils.escapeHTML(item.status)}
                    </div>
                    <div class="flex gap-2">
                        ${!isConcluido ? `
                            <button onclick="EncaminhamentoApp.executarAcao(${item.id}, 'concluir')" class="p-2 cdi-text-success hover:cdi-bg-success-light rounded-lg transition" title="Marcar como Concluído">
                                <i class="fas fa-check"></i>
                            </button>
                        ` : ''}
                        <button onclick="EncaminhamentoApp.executarAcao(${item.id}, 'excluir')" class="p-2 text-gray-400 hover:cdi-text-danger hover:cdi-bg-danger-light rounded-lg transition" title="Excluir">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    },

    async salvar(e) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');

        const dados = {
            paciente_id: form.paciente_id.value,
            data: form.data.value,
            urgencia: form.urgencia.value,
            destino: form.destino.value
        };

        if (!dados.paciente_id || !dados.data || !dados.destino) {
            CDIUtils.showToast("Preencha os campos obrigatórios", "warning");
            return;
        }

        btn.disabled = true;
        try {
            const res = await fetch('../api/salvar_encaminhamento.php', {
                method: 'POST',
                headers: CDIUtils.getHeaders(),
                body: JSON.stringify(dados)
            });
            const result = await res.json();
            if (result.status === 'sucesso') {
                CDIUtils.showToast("Encaminhamento registrado!", "success");
                this.toggleModal(false);
                this.carregarEncaminhamentos();
            }
        } catch (e) {
            CDIUtils.showToast("Erro ao salvar", "danger");
        } finally {
            btn.disabled = false;
        }
    },

    async executarAcao(id, acao) {
        const msg = acao === 'excluir' ? 'Deseja realmente excluir?' : 'Deseja marcar como concluído?';
        if (!confirm(msg)) return;

        try {
            const res = await fetch('../api/acao_encaminhamento.php', {
                method: 'POST',
                headers: CDIUtils.getHeaders(),
                body: JSON.stringify({ id, acao })
            });
            const result = await res.json();
            if (result.status === 'sucesso') {
                this.carregarEncaminhamentos();
                CDIUtils.showToast("Ação realizada com sucesso", "success");
            }
        } catch (e) {
            CDIUtils.showToast("Erro ao processar", "danger");
        }
    },

    bindEvents() {
        document.getElementById("formEncaminhamento")?.addEventListener("submit", (e) => this.salvar(e));
        document.getElementById("busca-idoso")?.addEventListener("input", () => this.carregarEncaminhamentos());
        document.getElementById("filtro-status")?.addEventListener("change", () => this.carregarEncaminhamentos());
        
        // Eventos do Modal
        document.getElementById("btn-novo-encaminhamento")?.addEventListener("click", () => this.toggleModal(true));
        document.getElementById("btn-fechar-modal")?.addEventListener("click", () => this.toggleModal(false));
        document.getElementById("btn-cancelar-modal")?.addEventListener("click", () => this.toggleModal(false));

        // Fechar ao clicar fora do modal
        window.addEventListener("click", (e) => {
            const modal = document.getElementById("modal-encaminhamento");
            if (e.target === modal) this.toggleModal(false);
        });
    }
};

document.addEventListener("DOMContentLoaded", () => EncaminhamentoApp.init());
