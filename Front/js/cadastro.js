/**
 * ARQUIVO: cadastro.js (Versão Final com Edição)
 */

const CadastroApp = {
  init() {
    this.bindEvents();
    this.aplicarMascaras();
    this.carregarListaEdicao();
    console.log("CadastroApp: Engine pronta com suporte a Edição.");
  },

  async carregarListaEdicao() {
    try {
      const res = await fetch("../api/get_cadastrados.php");
      const pacientes = await res.json();
      const select = document.getElementById("select-editar");
      if (select) {
        // Limpa opções antigas exceto a primeira
        select.innerHTML = '<option value="">Novo Cadastro...</option>';
        pacientes.forEach(p => {
          const opt = document.createElement("option");
          opt.value = p.id;
          opt.textContent = p.nome;
          select.appendChild(opt);
        });
      }
    } catch (e) { console.error(e); }
  },

  async carregarDadosParaEdicao(id) {
    if (!id) { this.resetarFormulario(); return; }

    try {
      const res = await fetch(`../api/get_paciente_por_id.php?id=${id}`);
      const result = await res.json();

      if (result.status === 'sucesso') {
        this.preencherFormulario(result.paciente);
      }
    } catch (e) { alert("Erro ao carregar dados."); }
  },

  preencherFormulario(p) {
    document.getElementById("paciente_id").value = p.id;
    document.getElementById("matricula").value = p.matricula || "";
    document.getElementById("nome").value = p.nome;
    document.getElementById("sexo").value = p.sexo || "";
    document.getElementById("cor_raca").value = p.cor_raca || "";
    document.getElementById("nis").value = p.nis || "";
    document.getElementById("data_nascimento").value = p.data_nascimento || "";
    document.getElementById("status").value = p.status || "ativo";
    
    if (typeof calcularIdade === 'function') calcularIdade();

    // Resetar checkboxes de benefícios
    const checkboxes = document.querySelectorAll('input[name="beneficios[]"]');
    checkboxes.forEach(cb => {
      cb.checked = p.beneficios && p.beneficios.includes(cb.value);
    });

    // Mudar visual para Edição
    document.getElementById("form-header-color").classList.replace("cdi-bg-primary", "cdi-bg-warning");
    document.getElementById("form-title").textContent = "Editando: " + p.nome;
    document.querySelector('button[type="submit"]').textContent = "Atualizar Cadastro";
  },

  resetarFormulario() {
    document.getElementById("cadastroForm").reset();
    document.getElementById("paciente_id").value = "";
    document.getElementById("form-header-color").classList.replace("cdi-bg-warning", "cdi-bg-primary");
    document.getElementById("form-title").textContent = "Novo Cadastro";
    document.querySelector('button[type="submit"]').textContent = "Salvar Cadastro";
  },

  bindEvents() {
    const form = document.getElementById("cadastroForm");
    const selectEditar = document.getElementById("select-editar");
    const inputData = document.getElementById("data_nascimento");

    selectEditar?.addEventListener("change", (e) => this.carregarDadosParaEdicao(e.target.value));

    // Lógica Exclusiva de Benefícios
    const checkNaoRecebe = document.getElementById("nao_recebe");
    const outrosBeneficios = document.querySelectorAll('input[name="beneficios[]"]:not(#nao_recebe)');

    checkNaoRecebe?.addEventListener("change", (e) => {
      if (e.target.checked) {
        outrosBeneficios.forEach(cb => {
          cb.checked = false;
          cb.disabled = true;
        });
      } else {
        outrosBeneficios.forEach(cb => cb.disabled = false);
      }
    });

    outrosBeneficios.forEach(cb => {
      cb.addEventListener("change", (e) => {
        if (e.target.checked && checkNaoRecebe) {
          checkNaoRecebe.checked = false;
        }
      });
    });

    // Validação de Ano (Impede anos com mais de 4 dígitos)
    inputData?.addEventListener("input", (e) => {
      const valor = e.target.value;
      const ano = valor.split("-")[0];
      if (ano && ano.length > 4) {
        e.target.value = valor.substring(0, 4) + valor.substring(ano.length);
        alert("⚠️ O ano deve ter no máximo 4 dígitos.");
      }
    });

    form?.addEventListener("submit", (e) => {
      e.preventDefault();
      this.handleSubmit(e.target);
    });
  },

  aplicarMascaras() {
    const nisInput = document.getElementById("nis");
    nisInput?.addEventListener("input", (e) => {
      let value = e.target.value.replace(/\D/g, "");
      if (value.length > 3 && value.length <= 8) value = value.replace(/^(\d{3})(\d+)/, "$1.$2");
      else if (value.length > 8 && value.length <= 10) value = value.replace(/^(\d{3})(\d{5})(\d+)/, "$1.$2.$3");
      else if (value.length > 10) value = value.replace(/^(\d{3})(\d{5})(\d{2})(\d{1})/, "$1.$2.$3-$4");
      e.target.value = value;
    });
  },

  async handleSubmit(form) {
    const formData = new FormData(form);
    const dados = {};
    formData.forEach((value, key) => {
      if (key.endsWith("[]")) {
        const cleanKey = key.replace("[]", "");
        if (!dados[cleanKey]) dados[cleanKey] = [];
        dados[cleanKey].push(value);
      } else { dados[key] = value; }
    });

    const isEdicao = !!dados.id;
    const url = isEdicao ? "../api/atualizar_paciente.php" : "../api/salvar_dados_cadastrados.php";

    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = "Processando...";

    try {
      const res = await fetch(url, {
        method: "POST",
        body: JSON.stringify(dados),
        headers: { "Content-Type": "application/json" },
      });
      const result = await res.json();

      if (result.status === "sucesso") {
        alert(isEdicao ? "✅ Cadastro atualizado!" : "✅ Salvo com sucesso!");
        if (!isEdicao) form.reset();
        this.carregarListaEdicao();
      } else {
        alert("❌ Erro: " + result.mensagem);
      }
    } catch (error) { alert("❌ Erro de comunicação."); }
    finally {
      btn.disabled = false;
      btn.textContent = isEdicao ? "Atualizar Cadastro" : "Salvar Cadastro";
    }
  },
};

document.addEventListener("DOMContentLoaded", () => CadastroApp.init());
