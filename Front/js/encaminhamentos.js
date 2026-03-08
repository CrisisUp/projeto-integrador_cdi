/**
 * ARQUIVO: encaminhamentos.js
 * Engine modular para gestão de encaminhamentos externos do CDI.
 */

const EncaminhamentoApp = {
  // 1. ESTADO INICIAL (Dados de Exemplo)
  dados: [
    {
      id: 1,
      paciente: "Ana Silva",
      destino: "Oftalmologista - Hospital Central",
      data: "2026-03-15",
      status: "Pendente",
      urgencia: "Alta",
    },
    {
      id: 2,
      paciente: "Carlos Oliveira",
      destino: "CRAS - Atualização Cadastral",
      data: "2026-03-10",
      status: "Concluído",
      urgencia: "Normal",
    },
  ],

  // 2. INICIALIZAÇÃO
  init() {
    this.render();
    this.bindEvents();
  },

  // 3. RENDERIZAÇÃO DA INTERFACE
  render() {
    const container = document.getElementById("lista-encaminhamentos");
    if (!container) return;

    if (this.dados.length === 0) {
      container.innerHTML = `
                <div class="p-20 text-center text-gray-400">
                    <i class="fas fa-folder-open text-4xl mb-4"></i>
                    <p>Nenhum encaminhamento registrado.</p>
                </div>`;
      return;
    }

    // Ordenar: Pendentes primeiro
    const dadosOrdenados = [...this.dados].sort((a, b) =>
      a.status === "Pendente" ? -1 : 1,
    );
    container.innerHTML = dadosOrdenados
      .map((item) => this.templateCard(item))
      .join("");
  },

  // 4. TEMPLATE DO CARD (HTML Dinâmico)
  templateCard(item) {
    const isConcluido = item.status === "Concluído";
    const corStatus = isConcluido
      ? "bg-green-100 text-green-700"
      : "bg-yellow-100 text-yellow-700";
    const corUrgencia =
      item.urgencia === "Alta" || item.urgencia === "Urgente"
        ? "text-red-600 font-bold"
        : "text-gray-500";

    return `
            <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm hover:shadow-md transition flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full ${isConcluido ? "bg-green-50 text-green-500" : "bg-blue-50 text-blue-500"} flex items-center justify-center text-xl">
                        <i class="fas ${isConcluido ? "fa-check-circle" : "fa-file-medical"}"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-bold text-gray-800 text-lg ${isConcluido ? "line-through opacity-50" : ""}">${item.paciente}</h3>
                        <p class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i> ${item.destino}</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-6">
                    <div class="text-center">
                        <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest">Data Prevista</span>
                        <span class="text-gray-700 font-medium">${CDIUtils.formatarDataBR(item.data)}</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest">Urgência</span>
                        <span class="${corUrgencia}">${item.urgencia}</span>
                    </div>
                    <div class="px-4 py-1 rounded-full text-xs font-bold ${corStatus}">
                        ${item.status}
                    </div>
                    <div class="flex gap-2">
                        ${
                          !isConcluido
                            ? `
                            <button onclick="EncaminhamentoApp.concluir(${item.id})" class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition" title="Marcar como Concluído">
                                <i class="fas fa-check"></i>
                            </button>`
                            : ""
                        }
                        <button onclick="EncaminhamentoApp.excluir(${item.id})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
  },

  // 5. EVENTOS E MODAL
  bindEvents() {
    // Abrir Modal
    document
      .getElementById("btn-novo-encaminhamento")
      ?.addEventListener("click", () => this.toggleModal(true));

    // Fechar Modal ao clicar fora (opcional)
    document
      .getElementById("modal-encaminhamento")
      ?.addEventListener("click", (e) => {
        if (e.target.id === "modal-encaminhamento") this.toggleModal(false);
      });

    // Submit do Formulário
    document
      .getElementById("form-encaminhamento")
      ?.addEventListener("submit", (e) => {
        e.preventDefault();
        this.handleSalvar(new FormData(e.target));
      });
  },

  toggleModal(show) {
    const modal = document.getElementById("modal-encaminhamento");
    if (modal) {
      show ? modal.classList.remove("hidden") : modal.classList.add("hidden");
      if (show) document.querySelector('input[name="paciente"]').focus();
    }
  },

  // 6. AÇÕES (CRUD)
  handleSalvar(formData) {
    const novo = {
      id: Date.now(),
      paciente: formData.get("paciente"),
      data: formData.get("data"),
      urgencia: formData.get("urgencia"),
      destino: formData.get("destino"),
      status: "Pendente",
    };

    this.dados.unshift(novo);
    this.toggleModal(false);
    this.render();
    document.getElementById("form-encaminhamento").reset();
  },

  concluir(id) {
    const item = this.dados.find((d) => d.id === id);
    if (item) {
      item.status = "Concluído";
      this.render();
    }
  },

  excluir(id) {
    if (confirm("Tem certeza que deseja remover este registro?")) {
      this.dados = this.dados.filter((d) => d.id !== id);
      this.render();
    }
  },
};

// Iniciar Engine
document.addEventListener("DOMContentLoaded", () => EncaminhamentoApp.init());
