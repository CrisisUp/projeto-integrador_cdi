/**
 * js/global.js - Utilidades e Lógica Global do CDI Digital
 */

const CDIUtils = {
  // 1. GESTÃO DE DATAS E CALENDÁRIO
  getMonthNames() {
    return [
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
    ];
  },

  getDaysInMonth(year, month) {
    return new Date(year, month + 1, 0).getDate();
  },

  getFirstDayOfMonth(year, month) {
    return new Date(year, month, 1).getDay();
  },

  formatarDataBR(dataISO) {
    if (!dataISO || dataISO === "---") return "---";
    const parts = dataISO.split("-");
    if (parts.length < 3) return dataISO;
    const [ano, mes, dia] = parts;
    return `${dia}/${mes}/${ano}`;
  },

  // 2. SEGURANÇA E ESCAPE (Prevenção de XSS)
  escapeHTML(str) {
    if (!str) return "";
    const div = document.createElement("div");
    div.textContent = str;
    return div.innerHTML;
  },

  /**
   * Retorna os cabeçalhos padrão para requisições Fetch,
   * incluindo o Token CSRF para proteção contra ataques.
   */
  getHeaders(extraHeaders = {}) {
    return {
      "Content-Type": "application/json",
      "X-CSRF-Token": window.csrfToken || "", // Pega o token gerado no PHP
      ...extraHeaders
    };
  },

  showToast(message, type = "success") {
    const toast = document.createElement("div");
    toast.className = `cdi-toast cdi-bg-${type} animate-fade-in`;
    toast.style.cssText = `
        position: fixed; bottom: 20px; right: 20px;
        padding: 12px 24px; border-radius: var(--radius-md);
        color: white; z-index: 9999; box-shadow: var(--shadow-lg);
    `;
    toast.innerText = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  },

  // 3. TEMA E APARÊNCIA (CARREGAMENTO INICIAL)
  initTheme() {
    // Aplica Cor Principal
    const savedColor = localStorage.getItem("theme-color") || "#1d9bf0";
    document.documentElement.style.setProperty("--primary-color", savedColor);
    document.documentElement.style.setProperty(
      "--primary-light",
      `color-mix(in srgb, ${savedColor}, transparent 85%)`,
    );

    // Aplica Modo Escuro
    if (localStorage.getItem("dark-mode") === "enabled") {
      document.body.classList.add("dark-mode");
    }
  },
};

// Executa a inicialização assim que o DOM estiver pronto
document.addEventListener("DOMContentLoaded", () => {
  CDIUtils.initTheme();
});

// Executa IMEDIATAMENTE (para evitar flash branco)
if (localStorage.getItem("dark-mode") === "enabled") {
  document.documentElement.classList.add("dark-mode");
}

/**
 * Função global para alternar o modo escuro
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
