/**
 * ARQUIVO: js/login.js
 * Gerencia a validação visual e o envio do formulário de login para o PHP.
 */

document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");

  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      // 1. CAPTURAR OS CAMPOS
      const emailInput = document.getElementById("email");
      const passwordInput = document.getElementById("password");
      const submitBtn = this.querySelector(".login-btn");

      // 2. VALIDAÇÃO BÁSICA (Lado do Cliente)
      if (!emailInput.value.trim() || !passwordInput.value.trim()) {
        event.preventDefault(); // Impede o envio se estiver vazio
        alert("Por favor, preencha todos os campos.");
        return;
      }

      // 3. FEEDBACK VISUAL DE CARREGAMENTO
      // Em vez de um alert que trava a página, mudamos o texto do botão
      if (submitBtn) {
        submitBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin mr-2"></i> Autenticando...';
        submitBtn.style.opacity = "0.7";
        submitBtn.style.cursor = "not-allowed";
      }

      console.log("Enviando credenciais para o servidor...");

      // NOTA: Não usamos event.preventDefault() aqui para permitir que o
      // formulário siga o 'action="auth.php"' definido no seu HTML.
    });
  }

  // 4. ANIMAÇÃO DOS ÍCONES (FOCO NOS INPUTS)
  const inputs = document.querySelectorAll(".form-input");

  inputs.forEach((input) => {
    // Quando o usuário clica no campo
    input.addEventListener("focus", function () {
      const icon = this.parentElement.querySelector(".input-icon");
      if (icon) {
        // Usa a cor primária definida no seu global.css
        icon.style.color = "var(--primary-color, #1d9bf0)";
        icon.style.transform = "scale(1.1)";
        icon.style.transition = "all 0.3s ease";
      }
    });

    // Quando o usuário sai do campo
    input.addEventListener("blur", function () {
      const icon = this.parentElement.querySelector(".input-icon");
      if (icon) {
        icon.style.color = "#9ca3af"; // Cinza padrão
        icon.style.transform = "scale(1)";
      }
    });
  });
});

/*

(function () {
  function c() {
    var b = a.contentDocument || a.contentWindow.document;
    if (b) {
      var d = b.createElement("script");
      d.innerHTML =
        "window.__CF$cv$params={r:'93abc31ae48f1a8c',t:'MTc0NjQwMTE3Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
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
