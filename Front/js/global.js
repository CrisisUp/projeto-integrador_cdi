/**
 * js/global.js - Utilidades e Lógica Global do CDI Digital
 */

const CDIUtils = {
    // 1. GESTÃO DE DATAS E CALENDÁRIO
    getMonthNames() {
        return ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
    },

    getDaysInMonth(year, month) {
        return new Date(year, month + 1, 0).getDate();
    },

    getFirstDayOfMonth(year, month) {
        return new Date(year, month, 1).getDay();
    },

    formatarDataBR(dataISO) {
        if (!dataISO) return "---";
        const [ano, mes, dia] = dataISO.split("-");
        return `${dia}/${mes}/${ano}`;
    },

    // 2. TEMA E APARÊNCIA (CARREGAMENTO INICIAL)
    initTheme() {
        // Aplica Cor Principal
        const savedColor = localStorage.getItem("theme-color") || "#1d9bf0";
        document.documentElement.style.setProperty("--primary-color", savedColor);
        document.documentElement.style.setProperty("--primary-light", savedColor + "26");
        
        // Aplica Modo Escuro
        if (localStorage.getItem("dark-mode") === "enabled") {
            document.body.classList.add("dark-mode");
        }
    }
};

// Executa a inicialização assim que o DOM estiver pronto
document.addEventListener("DOMContentLoaded", () => {
    CDIUtils.initTheme();
});

// Executa IMEDIATAMENTE (para evitar flash branco)
if (localStorage.getItem("dark-mode") === "enabled") {
    document.documentElement.classList.add("dark-mode");
    // Nota: Aplicamos no documentElement também para garantir cobertura total
}

/**
 * Função global para alternar o modo escuro (chamada pela página de configurações)
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
