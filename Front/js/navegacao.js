document.addEventListener("DOMContentLoaded", function () {
  const optionCards = document.querySelectorAll(".option-card");
  const confirmButton = document.getElementById("confirmButton");
  const logoutButton = document.querySelector(".logout-btn");
  let selectedOption = null;

  // Adicionar evento de clique a cada opção
  optionCards.forEach((card) => {
    card.addEventListener("click", function () {
      // Remover seleção anterior
      optionCards.forEach((c) => c.classList.remove("selected"));

      // Selecionar esta opção
      this.classList.add("selected");
      selectedOption = this.dataset.option;

      // Habilitar botão de confirmação
      confirmButton.disabled = false;
    });
  });

  // Evento de clique no botão de confirmação
  confirmButton.addEventListener("click", function () {
    if (selectedOption) {
      // Mapeamento para os nomes reais dos seus arquivos
      const opcao = this.dataset.option;
      const paginas = {
        cadastro: "cadastro.php",
        "atividade-convencional": "convencional.php",
        "atividade-enfermagem": "enfermagem.php",
        frequencia: "presenca.php",
        encaminhamentos: "#",
        configuracoes: "#",
      };

      const destino = paginas[selectedOption];

      if (destino !== "#") {
        window.location.href = destino;
      } else {
        alert("Esta página ainda não foi implementada.");
      }
    }
  });

  // Evento de clique no botão de logout
  logoutButton.addEventListener("click", function () {
    if (confirm("Deseja realmente sair do sistema?")) {
      window.location.href = "login.php"; // Removido o alert e ativado o link
    }
  });
});

/*

(function () {
  function c() {
    var b = a.contentDocument || a.contentWindow.document;
    if (b) {
      var d = b.createElement("script");
      d.innerHTML =
        "window.__CF$cv$params={r:'93b9bec4b40e1433',t:'MTc0NjU0Nzc5OS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
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
