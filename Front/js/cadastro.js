/**
 * ARQUIVO: cadastro.js (Refatorado)
 * Descrição: Engine de gerenciamento do formulário de pacientes.
 */

const CadastroApp = {
  // 1. Inicialização
  init() {
    this.bindEvents();
    console.log("CadastroApp: Engine carregada.");
  },

  // 2. Mapeamento de Eventos (Onde a mágica começa)
  bindEvents() {
    const form = document.getElementById("cadastroForm");
    if (!form) return;

    // Escuta mudanças na Data de Nascimento
    document
      .getElementById("data_nascimento")
      ?.addEventListener("change", (e) => this.handleIdade(e.target.value));

    // Escuta mudanças na Dieta
    document
      .getElementById("dieta_especial")
      ?.addEventListener("change", (e) =>
        this.toggleSection("dieta_especial_detalhes", e.target.value === "sim"),
      );

    // Escuta mudanças no Status (Desligamento)
    document
      .getElementById("status")
      ?.addEventListener("change", (e) => this.handleStatus(e.target.value));

    // Escuta mudanças nos Benefícios
    this.setupBeneficiosLogic();

    // Escuta o envio do formulário
    form.addEventListener("submit", (e) => this.handleSubmit(e));
  },

  // 3. Lógica de Negócio (Tratamento de dados)
  handleIdade(dataISO) {
    const resIdade = CDIUtils.calcularIdade(dataISO);
    const inputIdade = document.getElementById("idade");
    if (inputIdade) inputIdade.value = resIdade;

    const idadeNum = parseInt(resIdade);
    if (!isNaN(idadeNum)) this.definirFaixaEtaria(idadeNum);
  },

  definirFaixaEtaria(idade) {
    const inputFaixa = document.getElementById("faixa_etaria");
    if (!inputFaixa) return;

    let faixa = "adulto";
    if (idade < 3) faixa = "bebe";
    else if (idade < 12) faixa = "crianca";
    else if (idade < 18) faixa = "adolescente";
    else if (idade >= 60 && idade <= 64) faixa = "idoso_60_64";
    else if (idade >= 65 && idade <= 69) faixa = "idoso_65_69";
    else if (idade >= 70 && idade <= 74) faixa = "idoso_70_74";
    else if (idade >= 75) faixa = "idoso_75_mais";

    inputFaixa.value = faixa;
  },

  handleStatus(status) {
    const isDesligado = status === "desligado";
    this.toggleSection("data_desligamento_container", isDesligado);
    this.toggleSection("motivo_desligamento_container", isDesligado);
  },

  setupBeneficiosLogic() {
    const checkNaoRecebe = document.getElementById("nao_recebe");
    const outros = document.querySelectorAll(
      'input[name="beneficios"]:not(#nao_recebe)',
    );

    checkNaoRecebe?.addEventListener("change", (e) => {
      outros.forEach((cb) => {
        cb.checked = false;
        cb.disabled = e.target.checked;
      });
    });

    outros.forEach((cb) => {
      cb.addEventListener("change", () => {
        if (cb.checked && checkNaoRecebe) checkNaoRecebe.checked = false;
      });
    });
  },

  // 4. Utilitários de Interface
  toggleSection(id, show) {
    const el = document.getElementById(id);
    if (el) show ? el.classList.remove("hidden") : el.classList.add("hidden");
  },

  handleSubmit(e) {
    e.preventDefault();
    const data = new FormData(e.target);
    console.log("Dados capturados:", Object.fromEntries(data));
    alert("Dados validados com sucesso pela Engine!");
  },
};

// Dispara a aplicação
document.addEventListener("DOMContentLoaded", () => CadastroApp.init());
