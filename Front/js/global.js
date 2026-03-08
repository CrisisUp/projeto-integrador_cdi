/**
 * ARQUIVO: global.js
 * DESCRIÇÃO: O "Cérebro" visual e funcional do sistema CDI.
 * Carrega temas, formata dados e centraliza cálculos.
 */

// 1. EXECUÇÃO IMEDIATA (Prevenir o "flash" de cor errada)
(function () {
  const savedColor = localStorage.getItem("theme-color") || "#1d9bf0";
  const isDark = localStorage.getItem("dark-mode") === "true";

  applyThemeColor(savedColor);
  if (isDark) applyDarkMode(true);
})();

// 2. FUNÇÕES DE TEMA (Acessíveis por qualquer página)
function applyThemeColor(color) {
  document.documentElement.style.setProperty("--primary-color", color);
  document.documentElement.style.setProperty(
    "--primary-hover",
    shadeColor(color, -15),
  );
  document.documentElement.style.setProperty("--primary-light", color + "26"); // 15% opacidade
  document.documentElement.style.setProperty("--primary-glow", color + "33"); // 20% opacidade
  localStorage.setItem("theme-color", color);
}

function applyDarkMode(enabled) {
  const root = document.documentElement;

  if (enabled) {
    // Cores de Fundo (Escuras)
    root.style.setProperty("--white", "#1f2937"); // Cards e Sidebar
    root.style.setProperty("--bg-main", "#111827"); // Fundo da página

    // Cores de Texto (Alto Contraste)
    root.style.setProperty("--text-main", "#f9fafb"); // Texto principal (quase branco)
    root.style.setProperty("--text-muted", "#9ca3af"); // Texto secundário (cinza claro)

    // Bordas e Divisores
    root.style.setProperty("--border-light", "#374151");

    document.body?.classList.add("dark-mode");
  } else {
    // Cores de Fundo (Claras)
    root.style.setProperty("--white", "#ffffff");
    root.style.setProperty("--bg-main", "#f5f0e5");

    // Cores de Texto (Padrão)
    root.style.setProperty("--text-main", "#1f2937"); // Cinza muito escuro
    root.style.setProperty("--text-muted", "#6b7280"); // Cinza médio

    root.style.setProperty("--border-light", "#eeeeee");

    document.body?.classList.remove("dark-mode");
  }
  localStorage.setItem("dark-mode", enabled);
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
