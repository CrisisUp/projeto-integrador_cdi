/**
 * ARQUIVO: js/global.js
 * Este script roda em TODAS as páginas do sistema.
 */

// 1. APLICAÇÃO IMEDIATA (Evita o "flash" branco ao carregar)
(function () {
  const isDark = localStorage.getItem("dark-mode") === "enabled";
  const savedColor = localStorage.getItem("theme-color");

  // Aplica a classe dark-mode ao root antes mesmo do body carregar totalmente
  if (isDark) {
    document.documentElement.classList.add("dark-mode");
  }

  // Aplica a cor do tema salva nas variáveis CSS
  if (savedColor) {
    document.documentElement.style.setProperty("--primary-color", savedColor);
    document.documentElement.style.setProperty(
      "--primary-light",
      savedColor + "26",
    );
    document.documentElement.style.setProperty(
      "--primary-glow",
      `0 0 0 3px ${savedColor}33`,
    );
  }
})();

// 2. LÓGICA DE INTERAÇÃO (Roda após o DOM estar pronto)
document.addEventListener("DOMContentLoaded", () => {
  const isDark = localStorage.getItem("dark-mode") === "enabled";

  // Sincroniza o estado no body para garantir que os seletores CSS funcionem
  if (isDark) {
    document.body.classList.add("dark-mode");
  }
});

/**
 * Função Global para alternar o Modo Escuro
 * Pode ser chamada de qualquer página (especialmente da tela de configurações)
 */
function applyDarkMode(enabled) {
  if (enabled) {
    document.body.classList.add("dark-mode");
    document.documentElement.classList.add("dark-mode");
    localStorage.setItem("dark-mode", "enabled");
  } else {
    document.body.classList.remove("dark-mode");
    document.documentElement.classList.remove("dark-mode");
    localStorage.setItem("dark-mode", "disabled");
  }
}

// 3. UTILITÁRIOS (COM TRAVA DE SEGURANÇA)
// Aqui está a correção: verificamos se window.CDIUtils já existe
window.CDIUtils = window.CDIUtils || {
  calcularIdade: function (dataNascimento) {
    if (!dataNascimento) return "";
    const hoje = new Date();
    const nasc = new Date(dataNascimento);
    let idade = hoje.getFullYear() - nasc.getFullYear();
    const m = hoje.getMonth() - nasc.getMonth();
    if (m < 0 || (m === 0 && hoje.getDate() < nasc.getDate())) {
      idade--;
    }
    return idade >= 0 ? `${idade} anos` : "Data inválida";
  },

  formatarDataBR: function (dataISO) {
    if (!dataISO) return "";
    const [ano, mes, dia] = dataISO.split("-");
    return `${dia}/${mes}/${ano}`;
  },

  getDaysInMonth: (year, month) => new Date(year, month + 1, 0).getDate(),

  getMonthNames: () => [
    "Janeiro",
    "Fevereiro",
    "Março",
    "Abril",
    "Maio",
    "Junho",
    "Julho",
    "Agosto",
    "Setembro",
    "Outubro",
    "Novembro",
    "Dezembro",
  ],

  // Pega a cor do tema atual
  getThemeColor: () =>
    getComputedStyle(document.documentElement)
      .getPropertyValue("--primary-color")
      .trim(),
};

// 4. AUXILIAR: Escurecer/Clarear HEX para Hovers dinâmicos
function shadeColor(color, percent) {
  let R = parseInt(color.substring(1, 3), 16);
  let G = parseInt(color.substring(3, 5), 16);
  let B = parseInt(color.substring(5, 7), 16);

  R = parseInt((R * (100 + percent)) / 100);
  G = parseInt((G * (100 + percent)) / 100);
  B = parseInt((B * (100 + percent)) / 100);

  R = R < 255 ? R : 255;
  G = G < 255 ? G : 255;
  B = B < 255 ? B : 255;

  const RR =
    R.toString(16).length === 1 ? "0" + R.toString(16) : R.toString(16);
  const GG =
    G.toString(16).length === 1 ? "0" + G.toString(16) : G.toString(16);
  const BB =
    B.toString(16).length === 1 ? "0" + B.toString(16) : B.toString(16);

  return "#" + RR + GG + BB;
}
