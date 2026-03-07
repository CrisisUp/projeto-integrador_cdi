/**
 * ARQUIVO: cadastro.js
 * Descrição: Gerencia cálculos e comportamentos do formulário de cadastro.
 */

// 1. FUNÇÕES DE LÓGICA (Definições)

function calcularIdade() {
  const dataNascimento = document.getElementById("data_nascimento").value;

  if (dataNascimento) {
    const hoje = new Date();
    const nascimento = new Date(dataNascimento);
    let idade = hoje.getFullYear() - nascimento.getFullYear();

    const mesAtual = hoje.getMonth();
    const diaAtual = hoje.getDate();
    const mesNascimento = nascimento.getMonth();
    const diaNascimento = nascimento.getDate();

    if (
      mesAtual < mesNascimento ||
      (mesAtual === mesNascimento && diaAtual < diaNascimento)
    ) {
      idade--;
    }

    document.getElementById("idade").value = idade;

    // Definir faixa etária
    let faixaEtariaValue = "";
    if (idade < 3) faixaEtariaValue = "bebe";
    else if (idade < 12) faixaEtariaValue = "crianca";
    else if (idade < 18) faixaEtariaValue = "adolescente";
    else if (idade < 60) faixaEtariaValue = "adulto";
    else if (idade <= 64) faixaEtariaValue = "idoso_60_64";
    else if (idade <= 69) faixaEtariaValue = "idoso_65_69";
    else if (idade <= 74) faixaEtariaValue = "idoso_70_74";
    else faixaEtariaValue = "idoso_75_mais";

    document.getElementById("faixa_etaria").value = faixaEtariaValue;
  }
}

// 2. INICIALIZAÇÃO (Onde os eventos são ligados aos elementos)

document.addEventListener("DOMContentLoaded", function () {
  // --- Evento para o campo de data de nascimento ---
  const inputDataNasc = document.getElementById("data_nascimento");
  if (inputDataNasc) {
    inputDataNasc.addEventListener("change", calcularIdade);
  }

  // --- Mostrar/ocultar dieta especial ---
  const selectDieta = document.getElementById("dieta_especial");
  if (selectDieta) {
    selectDieta.addEventListener("change", function () {
      const detalhesDiv = document.getElementById("dieta_especial_detalhes");
      this.value === "sim"
        ? detalhesDiv.classList.remove("hidden")
        : detalhesDiv.classList.add("hidden");
    });
  }

  // --- Mostrar/ocultar campos de desligamento ---
  const selectStatus = document.getElementById("status");
  if (selectStatus) {
    selectStatus.addEventListener("change", function () {
      const containerData = document.getElementById(
        "data_desligamento_container",
      );
      const containerMotivo = document.getElementById(
        "motivo_desligamento_container",
      );

      if (this.value === "desligado") {
        containerData.classList.remove("hidden");
        containerMotivo.classList.remove("hidden");
      } else {
        containerData.classList.add("hidden");
        containerMotivo.classList.add("hidden");
        document.getElementById("data_desligamento").value = "";
        document.getElementById("motivo_desligamento").value = "";
      }
    });
  }

  // --- Gerenciar checkboxes de benefícios ---
  const checkNaoRecebe = document.getElementById("nao_recebe");
  if (checkNaoRecebe) {
    checkNaoRecebe.addEventListener("change", function () {
      const outrosBeneficios = document.querySelectorAll(
        'input[name="beneficios"]:not(#nao_recebe)',
      );
      outrosBeneficios.forEach((cb) => {
        cb.checked = false;
        cb.disabled = this.checked;
      });
    });

    // Lógica inversa: Se marcar qualquer outro, desmarca o "Não recebe"
    const outrosBeneficios = document.querySelectorAll(
      'input[name="beneficios"]:not(#nao_recebe)',
    );
    outrosBeneficios.forEach((cb) => {
      cb.addEventListener("change", function () {
        if (this.checked) checkNaoRecebe.checked = false;

        const algumMarcado = Array.from(outrosBeneficios).some(
          (c) => c.checked,
        );
        checkNaoRecebe.disabled = algumMarcado;
      });
    });
  }

  // --- Manipulação do envio do formulário ---
  const formCadastro = document.getElementById("cadastroForm");
  if (formCadastro) {
    formCadastro.addEventListener("submit", function (e) {
      e.preventDefault();
      alert("Formulário validado com sucesso!");
      // Aqui chamaremos a função de salvar no banco de dados futuramente
    });
  }

  // console.log("Módulo de Cadastro carregado com sucesso!");
});

/*

(function () {
  function c() {
    var b = a.contentDocument || a.contentWindow.document;
    if (b) {
      var d = b.createElement("script");
      d.innerHTML =
        "window.__CF$cv$params={r:'93baad4da4fca47f',t:'MTc0NjU1NzU3MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
      b.getElementsByTagName("head")[0].appendChild(d);
    }
  }
  if (document.body) {
    var a = document.createElement("iframe");
    a.height = 1;
    a.width = 1;
    a.style.position = "absolute";
    a.style.top = 0;
    a.style.left = 0;
    a.style.border = "none";
    a.style.visibility = "hidden";
    document.body.appendChild(a);
    if ("loading" !== document.readyState) c();
    else if (window.addEventListener)
      document.addEventListener("DOMContentLoaded", c);
    else {
      var e = document.onreadystatechange || function () {};
      document.onreadystatechange = function (b) {
        e(b);
        "loading" !== document.readyState &&
          ((document.onreadystatechange = e), c());
      };
    }
  }
})();

*/
