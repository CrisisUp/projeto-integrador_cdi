/**
 * ARQUIVO: convencional.js
 * Organizado por: Dados -> Funções -> Execução Segura
 */

// 1. DADOS DE EXEMPLO
const allPosts = [
  {
    id: 1,
    username: "TechGuru",
    date: "2023-06-15",
    content:
      "Acabei de lançar meu novo site! Confira e me diga o que você acha.",
  },
  {
    id: 2,
    username: "DesignMaster",
    date: "2023-06-15",
    content:
      "Dica de UI: Sempre considere a hierarquia visual dos seus elementos.",
  },
  {
    id: 3,
    username: "CodeNinja",
    date: "2023-06-14",
    content: "Aprender uma nova linguagem de programação é sempre empolgante!",
  },
  {
    id: 4,
    username: "DevLife",
    date: "2023-06-13",
    content:
      "Quando seu código funciona na primeira tentativa e você não sabe por quê 😅",
  },
  {
    id: 5,
    username: "TechNews",
    date: "2023-06-12",
    content: "Urgente: Novo framework JavaScript lançado!",
  },
  {
    id: 6,
    username: "UXResearcher",
    date: "2023-06-11",
    content: "Realizei testes com usuários hoje e obtive insights incríveis!",
  },
  {
    id: 7,
    username: "AIEnthusiast",
    date: "2023-06-10",
    content: "Os avanços em IA e aprendizado de máquina são incríveis!",
  },
];

// 2. VARIÁVEIS DE ESTADO
let currentDate = new Date();
let selectedDate = null;
const monthNames = [
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

// 3. FUNÇÕES (FERRAMENTAS)

function renderPosts(posts) {
  const container = document.getElementById("posts-container");
  if (!container) return;
  container.innerHTML = "";

  if (posts.length === 0) {
    container.innerHTML = `<div class="p-8 text-center text-gray-500"><p class="text-xl">Nenhuma postagem nesta data.</p></div>`;
    return;
  }

  posts.forEach((post) => {
    const postElement = document.createElement("div");
    postElement.className =
      "border-b border-gray-300 p-4 hover:bg-gray-100 transition-colors post";
    postElement.innerHTML = `
            <div class="flex">
                <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white">
                    <i class="fas fa-user"></i>
                </div>
                <div class="ml-3 flex-1">
                    <div class="flex items-center">
                        <span class="font-bold">${post.username}</span>
                        <span class="text-gray-500 mx-1">·</span>
                        <span class="text-gray-500">${new Date(post.date).toLocaleDateString("pt-BR")}</span>
                    </div>
                    <p class="mt-1">${post.content}</p>
                </div>
            </div>`;
    container.appendChild(postElement);
  });
}

function renderCalendar() {
  const monthYear = document.getElementById("monthYear");
  const calendarDays = document.getElementById("calendarDays");
  if (!monthYear || !calendarDays) return;

  monthYear.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
  calendarDays.innerHTML = "";

  const firstDay = new Date(
    currentDate.getFullYear(),
    currentDate.getMonth(),
    1,
  ).getDay();
  const daysInMonth = new Date(
    currentDate.getFullYear(),
    currentDate.getMonth() + 1,
    0,
  ).getDate();

  for (let i = 0; i < firstDay; i++) {
    calendarDays.appendChild(document.createElement("div"));
  }

  for (let day = 1; day <= daysInMonth; day++) {
    const dayElement = document.createElement("div");
    const dateString = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

    const hasPosts = allPosts.some((p) => p.date === dateString);
    dayElement.className = `calendar-day h-8 w-8 flex items-center justify-center rounded-full cursor-pointer ${hasPosts ? "text-blue-600 font-bold" : "text-gray-500"} ${selectedDate === dateString ? "bg-blue-500 text-white" : ""}`;
    dayElement.textContent = day;

    dayElement.onclick = () => {
      if (selectedDate === dateString) {
        selectedDate = null;
      } else {
        selectedDate = dateString;
        renderPosts(allPosts.filter((p) => p.date === selectedDate));
        document.getElementById("activeFilter").classList.remove("hidden");
        document.getElementById("filterDate").textContent =
          dayElement.textContent;
      }
      renderCalendar();
      if (!selectedDate) clearFilter();
    };
    calendarDays.appendChild(dayElement);
  }
}

function clearFilter() {
  selectedDate = null;
  const filterTag = document.getElementById("activeFilter");
  if (filterTag) filterTag.classList.add("hidden");
  renderCalendar();
  renderPosts(allPosts);
}

// 4. INICIALIZAÇÃO (O "ABRAÇO" DE SEGURANÇA)
document.addEventListener("DOMContentLoaded", () => {
  renderCalendar();
  renderPosts(allPosts);

  // Selecionar botões e adicionar eventos
  const btnPrev = document.getElementById("prevMonth");
  const btnNext = document.getElementById("nextMonth");
  const btnClear = document.getElementById("clearFilter");
  const monthYearTitle = document.getElementById("monthYear");

  if (btnPrev)
    btnPrev.onclick = () => {
      currentDate.setMonth(currentDate.getMonth() - 1);
      renderCalendar();
    };
  if (btnNext)
    btnNext.onclick = () => {
      currentDate.setMonth(currentDate.getMonth() + 1);
      renderCalendar();
    };
  if (btnClear) btnClear.onclick = clearFilter;

  // Lógica do seletor de mês/ano
  if (monthYearTitle) {
    monthYearTitle.onclick = () => {
      const selector = document.getElementById("monthYearSelector");
      if (selector)
        selector.style.display =
          selector.style.display === "block" ? "none" : "block";
    };
  }

  // console.log("Módulo Convencional Ativado!");
});

/*

(function () {
  function c() {
    var b = a.contentDocument || a.contentWindow.document;
    if (b) {
      var d = b.createElement("script");
      d.innerHTML =
        "window.__CF$cv$params={r:'93937ab93647621f',t:'MTc0NjE0NjU1NC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
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
