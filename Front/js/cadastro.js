/**
 * ARQUIVO: cadastro.js (Versão Final Consolidada)
 */

const CadastroApp = {
  init() {
    this.bindEvents();
    this.aplicarMascaras();
    console.log("CadastroApp: Engine carregada com validação.");
  },

  bindEvents() {
    const form = document.getElementById("cadastroForm");
    if (!form) return;

    // Cálculo automático de idade
    document
      .getElementById("data_nascimento")
      ?.addEventListener("change", (e) => this.handleIdade(e.target.value));

    // Lógica de Benefícios (Bloqueia outros se "Não recebe" estiver marcado)
    this.setupBeneficiosLogic();

    // Envio do formulário
    form.addEventListener("submit", (e) => this.handleSubmit(e));
  },

  handleIdade(dataISO) {
    if (!dataISO) return;
    const hoje = new Date();
    const nasc = new Date(dataISO);
    let idade = hoje.getFullYear() - nasc.getFullYear();
    const m = hoje.getMonth() - nasc.getMonth();
    if (m < 0 || (m === 0 && hoje.getDate() < nasc.getDate())) idade--;

    const inputIdade = document.getElementById("idade");
    if (inputIdade) inputIdade.value = idade + " anos";
  },

  setupBeneficiosLogic() {
    const checkNaoRecebe = document.getElementById("nao_recebe");
    const outros = document.querySelectorAll(
      'input[name="beneficios[]"]:not(#nao_recebe)',
    );

    checkNaoRecebe?.addEventListener("change", (e) => {
      outros.forEach((cb) => {
        cb.checked = false;
        cb.disabled = e.target.checked;
      });
    });
  },

  aplicarMascaras() {
    const nisInput = document.getElementById("nis");
    if (!nisInput) return;

    nisInput.addEventListener("input", (e) => {
      let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não é número

      // Aplica a formatação 000.00000.00-0
      if (value.length > 3 && value.length <= 8) {
        value = value.replace(/^(\d{3})(\d+)/, "$1.$2");
      } else if (value.length > 8 && value.length <= 10) {
        value = value.replace(/^(\d{3})(\d{5})(\d+)/, "$1.$2.$3");
      } else if (value.length > 10) {
        value = value.replace(/^(\d{3})(\d{5})(\d{2})(\d{1})/, "$1.$2.$3-$4");
      }

      e.target.value = value;
    });
  },

  // Método de Validação
  validarFormulario(dados) {
    const obrigatorios = {
      matricula: "Nº Matrícula",
      nome: "Nome do Usuário",
      sexo: "Sexo",
      cor_raca: "Cor/Raça",
      data_nascimento: "Data de Nascimento",
      nis: "NIS",
    };

    for (let campo in obrigatorios) {
      if (!dados[campo] || dados[campo].trim() === "") {
        alert(`⚠️ O campo [${obrigatorios[campo]}] é obrigatório.`);
        const el =
          document.getElementsByName(campo)[0] ||
          document.getElementById(campo);
        el?.focus();
        return false;
      }
    }

    // Verifica se o array de benefícios existe e tem pelo menos um item
    if (!dados.beneficios || dados.beneficios.length === 0) {
      alert(
        "⚠️ Você deve selecionar pelo menos uma opção em 'Benefícios'. Se não houver nenhum, marque 'Não recebe'.",
      );
      document.getElementById("nao_recebe")?.focus();
      return false;
    }

    // Validação extra: O NIS precisa ter 11 dígitos numéricos
    const nisLimpo = dados.nis.replace(/\D/g, ""); // Remove pontos ou espaços
    if (nisLimpo.length !== 11) {
      alert("⚠️ O NIS deve conter exatamente 11 dígitos.");
      document.getElementsByName("nis")[0]?.focus();
      return false;
    }

    return true;
  },

  async handleSubmit(e) {
    e.preventDefault();
    const form = e.target;

    // 1. Captura os dados
    const formData = new FormData(form);
    const dados = {};
    formData.forEach((value, key) => {
      if (key.endsWith("[]")) {
        const cleanKey = key.replace("[]", "");
        if (!dados[cleanKey]) dados[cleanKey] = [];
        dados[cleanKey].push(value);
      } else {
        dados[key] = value;
      }
    });

    // 2. Valida antes de enviar
    if (!this.validarFormulario(dados)) return;

    // 3. Feedback visual no botão
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Salvando...';

    try {
      // Ajustado para o nome do arquivo que funcionou anteriormente
      const resposta = await fetch("salvar_dados_cadastrados.php", {
        method: "POST",
        body: JSON.stringify(dados),
        headers: { "Content-Type": "application/json" },
      });

      const resultado = await resposta.json();

      if (resultado.status === "sucesso") {
        alert("✅ Cadastro de " + dados.nome + " realizado com sucesso!");
        form.reset();
        if (document.getElementById("idade"))
          document.getElementById("idade").value = "";
      } else {
        alert("❌ Erro: " + (resultado.mensagem || "Falha ao salvar"));
      }
    } catch (error) {
      console.error("Erro técnico:", error);
      alert("❌ Falha na comunicação com o servidor.");
    } finally {
      btn.disabled = false;
      btn.textContent = originalText;
    }
  },
};

document.addEventListener("DOMContentLoaded", () => CadastroApp.init());
