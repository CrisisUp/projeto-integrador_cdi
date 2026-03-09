/**
 * ARQUIVO: js/configuracoes.js
 */

document.addEventListener("DOMContentLoaded", function () {
  const darkModeSwitch = document.getElementById("dark-mode-switch");
  const formSenha = document.getElementById("formAlterarSenha");

  // 1. SINCRONIZAÇÃO INICIAL DO TEMA
  const isDarkEnabled = localStorage.getItem("dark-mode") === "enabled";
  if (darkModeSwitch) darkModeSwitch.checked = isDarkEnabled;
  updateColorDots();

  // 2. EVENTO DE MUDANÇA (MODO ESCURO)
  if (darkModeSwitch) {
    darkModeSwitch.addEventListener("change", function (e) {
      if (typeof applyDarkMode === "function") {
        applyDarkMode(e.target.checked);
      }
    });
  }

  // 3. LÓGICA DE ALTERAÇÃO DE SENHA
  if (formSenha) {
    formSenha.addEventListener("submit", async function (e) {
      e.preventDefault();

      const btn = formSenha.querySelector('button[type="submit"]');
      const senha_atual = document.getElementById("senha_atual").value;
      const nova_senha = document.getElementById("nova_senha").value;
      const confirmacao = document.getElementById("confirmacao").value;

      // Validação básica no cliente
      if (nova_senha !== confirmacao) {
        alert("⚠️ A nova senha e a confirmação não são iguais.");
        return;
      }

      if (nova_senha.length < 6) {
        alert("⚠️ A nova senha deve ter pelo menos 6 caracteres.");
        return;
      }

      btn.disabled = true;
      btn.textContent = "Processando...";

      try {
        const resposta = await fetch("../api/alterar_senha.php", {
          method: "POST",
          body: JSON.stringify({ senha_atual, nova_senha, confirmacao }),
          headers: { "Content-Type": "application/json" },
        });

        const resultado = await resposta.json();

        if (resultado.status === "sucesso") {
          alert("✅ " + resultado.mensagem);
          formSenha.reset();
        } else {
          alert("❌ " + resultado.mensagem);
        }
      } catch (error) {
        alert("❌ Erro ao conectar com o servidor.");
      } finally {
        btn.disabled = false;
        btn.textContent = "Atualizar Senha";
      }
    });
  }
});

// 4. FUNÇÕES DE TEMA

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
  return "#" + vals.slice(0, 3).map((x) => parseInt(x).toString(16).padStart(2, "0")).join("");
}
