document.addEventListener("DOMContentLoaded", function () {
  const optionCards = document.querySelectorAll(".option-card");
  const confirmButton = document.getElementById("confirmButton");
  const logoutButton = document.querySelector(".logout-btn");
  let selectedOption = null;

  // 1. Mapeamento de páginas (Fora dos eventos para ser global)
  const paginas = {
    cadastro: "cadastro.php",
    "atividade-convencional": "convencional.php",
    "atividade-enfermagem": "enfermagem.php",
    frequencia: "presenca.php",
    encaminhamentos: "encaminhamentos.php",
    configuracoes: "configuracoes.php",
  };

  // 2. Evento de clique em cada card
  optionCards.forEach((card) => {
    card.addEventListener("click", function () {
      // Remover seleção visual de todos os cards
      optionCards.forEach((c) => {
        c.classList.remove("selected", "ring-4", "ring-blue-400");
      });

      // Adicionar seleção visual ao card clicado
      this.classList.add("selected", "ring-4", "ring-blue-400");

      // Pegar o valor do data-option
      selectedOption = this.dataset.option;

      // Habilitar o botão e dar feedback visual
      confirmButton.disabled = false;
      confirmButton.classList.remove("opacity-50", "cursor-not-allowed");
      confirmButton.classList.add("bg-blue-600", "shadow-lg");
    });

    // Atalho: Duplo clique no card entra direto
    card.addEventListener("dblclick", function () {
      const opt = this.dataset.option;
      if (paginas[opt]) window.location.href = paginas[opt];
    });
  });

  // 3. Evento de clique no botão de confirmação
  confirmButton.addEventListener("click", function () {
    if (selectedOption && paginas[selectedOption]) {
      window.location.href = paginas[selectedOption];
    } else {
      alert("Por favor, selecione uma opção válida.");
    }
  });

  // 4. Lógica de Logout (opcional, caso queira que o botão funcione)
  if (logoutButton) {
    logoutButton.addEventListener("click", () => {
      window.location.href = "login.php";
    });
  }
});
