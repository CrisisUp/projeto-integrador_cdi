/**
 * js/navegacao.js - Lógica da Dashboard e Menu
 */

document.addEventListener("DOMContentLoaded", function () {
  const optionCards = document.querySelectorAll(".option-card");
  const confirmButton = document.getElementById("confirmButton");
  let selectedOption = null;

  // 1. MAPEAMENTO DE PÁGINAS
  const paginas = {
    cadastro: "cadastro.php",
    "atividade-convencional": "convencional.php",
    "atividade-enfermagem": "enfermagem.php",
    frequencia: "presenca.php",
    encaminhamentos: "encaminhamentos.php",
    configuracoes: "configuracoes.php",
  };

  // 2. CARREGAR ESTATÍSTICAS DO DASHBOARD
  async function carregarDashboard() {
    try {
      const res = await fetch("../api/get_dashboard_stats.php");
      const data = await res.json();

      if (data.status === "sucesso") {
        const s = data.stats;
        
        // Atualiza Presentes com comparativo
        const elPresentes = document.getElementById("stat-presentes");
        const elTotalEsperado = document.getElementById("label-total-esperado");
        if (elPresentes) elPresentes.textContent = s.presentes;
        if (elTotalEsperado) elTotalEsperado.textContent = `de ${s.total_esperado} esperados`;

        // Atualiza Evoluções com alerta de pendência
        const elEvolucoes = document.getElementById("stat-evolucoes");
        const elPendentes = document.getElementById("label-evolucoes-pendentes");
        if (elEvolucoes) elEvolucoes.textContent = s.evolucoes;
        if (elPendentes) {
          elPendentes.textContent = s.evolucoes_pendentes > 0 
            ? `${s.evolucoes_pendentes} pendentes hoje` 
            : "Tudo em dia";
          
          // Muda a cor se houver pendência
          if (s.evolucoes_pendentes > 0) {
            elPendentes.classList.add("cdi-text-danger");
            elPendentes.classList.remove("text-gray-400");
          } else {
            elPendentes.classList.remove("cdi-text-danger");
            elPendentes.classList.add("cdi-text-success");
          }
        }

        // Urgentes e Total
        document.getElementById("stat-urgentes").textContent = s.urgentes;
        document.getElementById("stat-total").textContent = s.total_ativos;
      }
    } catch (e) {
      console.error("Erro ao carregar dashboard:", e);
    }
  }

  carregarDashboard();

  // 3. SELEÇÃO DE OPÇÕES NO MENU
  optionCards.forEach((card) => {
    card.addEventListener("click", function () {
      optionCards.forEach((c) => {
        c.classList.remove("selected", "ring-4", "ring-blue-400");
      });

      this.classList.add("selected", "ring-4", "ring-blue-400");
      selectedOption = this.dataset.option;

      confirmButton.disabled = false;
      confirmButton.classList.remove("opacity-50", "cursor-not-allowed");
      confirmButton.classList.add("bg-blue-600", "shadow-lg");
    });

    card.addEventListener("dblclick", function () {
      const opt = this.dataset.option;
      if (paginas[opt]) window.location.href = paginas[opt];
    });
  });

  confirmButton.addEventListener("click", function () {
    if (selectedOption && paginas[selectedOption]) {
      window.location.href = paginas[selectedOption];
    } else {
      alert("Por favor, selecione uma opção válida.");
    }
  });
});
