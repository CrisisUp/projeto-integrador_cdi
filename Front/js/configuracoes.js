/**
 * ARQUIVO: js/configuracoes.js
 */

document.addEventListener("DOMContentLoaded", function () {
  const darkModeSwitch = document.getElementById("dark-mode-switch");

  // 1. SINCRONIZAÇÃO INICIAL
  const isDarkEnabled = localStorage.getItem("dark-mode") === "enabled";
  const savedColor = localStorage.getItem("theme-color") || "#1d9bf0";

  // Ajusta o Switch visualmente sem disparar novos eventos
  if (darkModeSwitch) {
    darkModeSwitch.checked = isDarkEnabled;
  }

  // Atualiza os círculos de seleção de cor
  updateColorDots();

  // 2. EVENTO DE MUDANÇA (MODO ESCURO)
  if (darkModeSwitch) {
    darkModeSwitch.addEventListener("change", function (e) {
      if (typeof applyDarkMode === "function") {
        applyDarkMode(e.target.checked);
      }
    });
  }
});

// 3. FUNÇÕES DE TEMA (FORA DO DOMCONTENTLOADED PARA SEREM GLOBAIS)

function setThemeColor(color) {
  if (!color) return;

  const root = document.documentElement;
  root.style.setProperty("--primary-color", color);
  root.style.setProperty("--primary-light", color + "26");

  localStorage.setItem("theme-color", color);
  updateColorDots();
}

function updateColorDots() {
  const activeColor = localStorage.getItem("theme-color") || "#1d9bf0";

  document.querySelectorAll(".color-dot").forEach((dot) => {
    // Pegamos a cor de fundo e convertemos para HEX para comparar corretamente
    const dotBgRaw = window.getComputedStyle(dot).backgroundColor;
    const dotHex = rgbToHex(dotBgRaw);

    // Se a cor da bolinha for igual à cor salva, ela ganha a classe 'active'
    if (dotHex.toLowerCase() === activeColor.toLowerCase()) {
      dot.classList.add("active");
    } else {
      dot.classList.remove("active");
    }
  });
}

// Auxiliar: Conversão necessária porque o navegador lê cores em RGB
function rgbToHex(rgb) {
  if (!rgb || rgb.startsWith("#")) return rgb;
  const vals = rgb.match(/\d+/g);
  if (!vals || vals.length < 3) return rgb;
  return (
    "#" +
    vals
      .slice(0, 3)
      .map((x) => parseInt(x).toString(16).padStart(2, "0"))
      .join("")
  );
}
