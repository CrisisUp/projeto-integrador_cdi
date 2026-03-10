/**
 * ARQUIVO: encaminhamentos.js (Versão com Vínculo ao Paciente)
 */

const EncaminhamentoApp = {
    dados: [],

    async init() {
        await this.carregar();
        this.bindEvents();
        await this.carregarListaPacientes();
    },

    async carregarListaPacientes() {
        try {
            const res = await fetch("../api/get_cadastrados.php");
            const pacientes = await res.json();
            const select = document.getElementById("select-pacientes");
            if (select) {
                pacientes.forEach(p => {
                    const opt = document.createElement("option");
                    opt.value = p.id;
                    opt.textContent = `${p.nome} (Matrícula: ${p.matricula || '---'})`;
                    select.appendChild(opt);
                });
            }
        } catch (e) {
            console.error("Erro ao carregar lista de pacientes:", e);
        }
    },

    async carregar() {
        const busca = document.querySelector('input[placeholder="Digite o nome do idoso..."]').value;
        const status = document.querySelector('select').value;

        const url = `../api/get_encaminhamentos.php?busca=${encodeURIComponent(busca)}&status=${encodeURIComponent(status)}`;

        try {
            const res = await fetch(url);
            this.dados = await res.json();
            this.render();
        } catch (e) {
            console.error("Erro ao carregar encaminhamentos:", e);
        }
    },

    render() {
        const container = document.getElementById("lista-encaminhamentos");
        if (!container) return;

        if (this.dados.length === 0) {
            container.innerHTML = `
                <div class="p-20 text-center text-gray-400">
                    <i class="fas fa-folder-open text-4xl mb-4"></i>
                    <p>Nenhum encaminhamento encontrado.</p>
                </div>`;
            return;
        }

        container.innerHTML = this.dados
            .map((item) => this.templateCard(item))
            .join("");
    },

    templateCard(item) {
        const isConcluido = item.status === "Concluído";
        const corStatus = isConcluido ? "bg-green-100 text-green-700" : "bg-yellow-100 text-yellow-700";
        const corUrgencia = (item.urgencia === "Alta" || item.urgencia === "Urgente") ? "text-red-600 font-bold" : "text-gray-500";
        const autor = item.funcionario_nome || "Sistema";

        return `
            <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm hover:shadow-md transition flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full ${isConcluido ? 'bg-green-50 text-green-500' : 'bg-blue-50 text-blue-500'} flex items-center justify-center text-xl">
                        <i class="fas ${isConcluido ? 'fa-check-circle' : 'fa-file-medical'}"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-bold text-gray-800 text-lg ${isConcluido ? 'line-through opacity-50' : ''}">${item.paciente_nome}</h3>
                        <p class="text-xs text-blue-500 font-bold uppercase tracking-tighter mb-1">Matrícula: ${item.matricula || '---'}</p>
                        <p class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i> ${item.destino}</p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase">Registrado por: ${autor}</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-6">
                    <div class="text-center">
                        <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest">Data Prevista</span>
                        <span class="text-gray-700 font-medium">${new Date(item.data).toLocaleDateString('pt-BR')}</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest">Urgência</span>
                        <span class="${corUrgencia}">${item.urgencia}</span>
                    </div>
                    <div class="px-4 py-1 rounded-full text-xs font-bold ${corStatus}">
                        ${item.status}
                    </div>
                    <div class="flex gap-2">
                        ${!isConcluido ? `
                            <button onclick="EncaminhamentoApp.executarAcao(${item.id}, 'concluir')" class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition" title="Marcar como Concluído">
                                <i class="fas fa-check"></i>
                            </button>` : ''}
                        <button onclick="EncaminhamentoApp.executarAcao(${item.id}, 'excluir')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>`;
    },

    bindEvents() {
        document.querySelector('input[placeholder="Digite o nome do idoso..."]').addEventListener('input', () => this.carregar());
        document.querySelector('select').addEventListener('change', () => this.carregar());

        document.getElementById("btn-novo-encaminhamento")?.addEventListener("click", () => this.toggleModal(true));
        document.getElementById("form-encaminhamento")?.addEventListener("submit", (e) => {
            e.preventDefault();
            this.handleSalvar(e.target);
        });
    },

    async handleSalvar(form) {
        const formData = new FormData(form);
        const dados = {
            paciente_id: formData.get("paciente_id"),
            data: formData.get("data"),
            urgencia: formData.get("urgencia"),
            destino: formData.get("destino")
        };

        try {
            const res = await fetch('../api/salvar_encaminhamento.php', {
                method: 'POST',
                body: JSON.stringify(dados),
                headers: { 'Content-Type': 'application/json' }
            });
            const result = await res.json();
            if (result.status === 'sucesso') {
                this.toggleModal(false);
                form.reset();
                await this.carregar();
            } else {
                alert("Erro ao salvar: " + result.mensagem);
            }
        } catch (e) {
            alert("Erro técnico ao salvar.");
        }
    },

    async executarAcao(id, acao) {
        if (acao === 'excluir' && !confirm("Deseja realmente excluir este registro?")) return;

        try {
            const res = await fetch('../api/acao_encaminhamento.php', {
                method: 'POST',
                body: JSON.stringify({ id, acao }),
                headers: { 'Content-Type': 'application/json' }
            });
            await this.carregar();
        } catch (e) {
            alert("Falha na comunicação com o servidor.");
        }
    },

    toggleModal(show) {
        const modal = document.getElementById("modal-encaminhamento");
        if (modal) {
            show ? modal.classList.remove("hidden") : modal.classList.add("hidden");
            
            if (show) {
                // Bloqueia datas retroativas
                const inputData = document.querySelector('input[name="data"]');
                if (inputData) {
                    const hoje = new Date().toISOString().split('T')[0];
                    inputData.setAttribute('min', hoje);
                    inputData.value = hoje; // Define hoje como padrão
                }
            }
        }
    }
};

document.addEventListener("DOMContentLoaded", () => EncaminhamentoApp.init());
