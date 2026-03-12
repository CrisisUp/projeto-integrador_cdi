/**
 * ARQUIVO: js/configuracoes.js - Integrado ao CDIUtils
 */

document.addEventListener("DOMContentLoaded", function () {
  const darkModeSwitch = document.getElementById("dark-mode-switch");
  const formSenha = document.getElementById("formAlterarSenha");

  // 1. SINCRONIZAÇÃO INICIAL
  if (darkModeSwitch) {
    darkModeSwitch.checked = localStorage.getItem("dark-mode") === "enabled";
  }
  updateColorDots();

  // 2. MODO ESCURO
  if (darkModeSwitch) {
    darkModeSwitch.addEventListener("change", function (e) {
      if (typeof applyDarkMode === "function") {
        applyDarkMode(e.target.checked);
        CDIUtils.showToast(
          e.target.checked ? "Modo escuro ativado" : "Modo claro ativado",
          "info",
        );
      }
    });
  }

  // 3. ALTERAÇÃO DE SENHA
  if (formSenha) {
    formSenha.addEventListener("submit", async function (e) {
      e.preventDefault();

      const btn = formSenha.querySelector('button[type="submit"]');
      const senha_atual = document.getElementById("senha_atual").value;
      const nova_senha = document.getElementById("nova_senha").value;
      const confirmacao = document.getElementById("confirmacao").value;

      // Validações usando o Toast do sistema
      if (nova_senha !== confirmacao) {
        CDIUtils.showToast("As senhas não coincidem", "danger");
        return;
      }

      if (nova_senha.length < 6) {
        CDIUtils.showToast(
          "A senha deve ter no mínimo 6 caracteres",
          "warning",
        );
        return;
      }

      btn.disabled = true;
      btn.innerHTML =
        '<i class="fas fa-spinner fa-spin mr-2"></i> Processando...';

      try {
        const resposta = await fetch("../api/alterar_senha.php", {
          method: "POST",
          body: JSON.stringify({ senha_atual, nova_senha, confirmacao }),
          headers: { "Content-Type": "application/json" },
        });

        const resultado = await resposta.json();

        if (resultado.status === "sucesso") {
          CDIUtils.showToast(resultado.mensagem, "success");
          formSenha.reset();
        } else {
          CDIUtils.showToast(resultado.mensagem, "danger");
        }
      } catch (error) {
        CDIUtils.showToast("Erro ao conectar com o servidor", "danger");
      } finally {
        btn.disabled = false;
        btn.textContent = "Atualizar Senha";
      }
    });
  }
});

// 4. FUNÇÕES DE TEMA (Globais para o onclick do HTML)

window.setThemeColor = function (color) {
  if (!color) return;

  // Salva e aplica usando a lógica centralizada no CDIUtils
  localStorage.setItem("theme-color", color);
  CDIUtils.initTheme();

  updateColorDots();
  CDIUtils.showToast("Cor do tema atualizada!", "success");
};

function updateColorDots() {
  const activeColor = localStorage.getItem("theme-color") || "#1d9bf0";
  document.querySelectorAll(".color-dot").forEach((dot) => {
    // Pegamos a cor de fundo do elemento
    const dotBgRaw = window.getComputedStyle(dot).backgroundColor;
    const dotHex = rgbToHex(dotBgRaw);

    if (dotHex.toLowerCase() === activeColor.toLowerCase()) {
      dot.classList.add("active");
    } else {
      dot.classList.remove("active");
    }
  });
}

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
