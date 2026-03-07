// Manipulação do formulário de login
document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    // Obter valores dos campos
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Aqui você adicionaria a lógica de autenticação
    // Por exemplo, enviar os dados para um script PHP via AJAX

    // Simulação de login bem-sucedido
    console.log("Tentativa de login com:", {
      email,
      password,
    });

    // Redirecionar para a página principal após login bem-sucedido
    // Na implementação real, isso seria feito após verificar as credenciais
    setTimeout(() => {
      alert("Login bem-sucedido! Redirecionando...");
      // window.location.href = 'index.php'; // Descomentar para redirecionar
      window.location.href = "navegacao.php";
    }, 1000);
  });

// Animação nos campos de entrada
const inputs = document.querySelectorAll(".form-input");
inputs.forEach((input) => {
  input.addEventListener("focus", function () {
    this.parentElement.querySelector(".input-icon").style.color = "#1d9bf0";
  });

  input.addEventListener("blur", function () {
    this.parentElement.querySelector(".input-icon").style.color = "#9ca3af";
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
