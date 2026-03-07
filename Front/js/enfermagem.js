/**
 * ARQUIVO: enfermagem.js
 * DESCRIÇÃO: Gerencia o calendário e as postagens da página de enfermagem.
 */

// 1. DADOS DE EXEMPLO (Os "Ingredientes")
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
    content:
      "Aprender uma nova linguagem de programação é sempre empolgante! Comecei com Rust.",
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
    content:
      "Urgente: Novo framework JavaScript lançado! Promete resolver tudo.",
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

// 2. VARIÁVEIS DE CONTROLE DO CALENDÁRIO
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

// 3. FUNÇÕES DE LÓGICA (As "Ferramentas")

// Renderiza as postagens na tela
function renderPosts(posts) {
  const postsContainer = document.getElementById("posts-container");
  if (!postsContainer) return;

  postsContainer.innerHTML = "";

  if (posts.length === 0) {
    postsContainer.innerHTML = `
            <div class="p-8 text-center text-gray-500">
                <p class="text-xl">Nenhuma postagem encontrada para esta data</p>
                <p class="mt-2">Tente selecionar uma data diferente</p>
            </div>
        `;
    return;
  }

  posts.forEach((post) => {
    const postDate = new Date(post.date);
    const formattedDate = postDate.toLocaleDateString("pt-BR", {
      day: "numeric",
      month: "short",
      year:
        postDate.getFullYear() !== new Date().getFullYear()
          ? "numeric"
          : undefined,
    });

    const postElement = document.createElement("div");
    postElement.className =
      "border-b border-gray-300 p-4 hover:bg-gray-100 transition-colors post";
    postElement.innerHTML = `
            <div class="flex">
                <div class="w-12 h-12 rounded-full bg-blue-500 flex-shrink-0 flex items-center justify-center overflow-hidden">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <div class="flex items-center">
                        <span class="font-bold">${post.username}</span>
                        <span class="text-gray-500 mx-1">·</span>
                        <span class="text-gray-500">${formattedDate}</span>
                    </div>
                    <p class="mt-1 mb-3">${post.content}</p>
                </div>
            </div>
        `;
    postsContainer.appendChild(postElement);

    // Pequena animação de entrada
    setTimeout(() => {
      postElement.classList.add("post-enter-active");
    }, 10);
  });
}

// Desenha o calendário conforme o mês atual
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

  // Espaços vazios antes do dia 1
  for (let i = 0; i < firstDay; i++) {
    const emptyDay = document.createElement("div");
    emptyDay.className =
      "h-8 w-8 flex items-center justify-center text-gray-400";
    calendarDays.appendChild(emptyDay);
  }

  // Dias do mês
  for (let day = 1; day <= daysInMonth; day++) {
    const dayElement = document.createElement("div");
    const dateString = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

    const hasPosts = allPosts.some((post) => post.date === dateString);
    dayElement.className = `calendar-day h-8 w-8 flex items-center justify-center rounded-full ${hasPosts ? "font-medium text-blue-600" : "text-gray-500"}`;

    if (selectedDate === dateString) dayElement.classList.add("selected");

    dayElement.textContent = day;

    dayElement.addEventListener("click", () => {
      if (selectedDate === dateString) {
        clearFilter();
        return;
      }
      selectedDate = dateString;
      renderCalendar(); // Re-renderiza para mostrar o dia selecionado
      const filtered = allPosts.filter((p) => p.date === selectedDate);
      renderPosts(filtered);

      // Atualiza o aviso de filtro ativo
      document.getElementById("activeFilter").classList.remove("hidden");
      document.getElementById("filterDate").textContent = new Date(
        dateString,
      ).toLocaleDateString("pt-BR");
    });

    calendarDays.appendChild(dayElement);
  }
}

// Inicializa o seletor de Mês/Ano (aquele que abre ao clicar no título)
function initMonthYearSelector() {
  const monthYearTitle = document.getElementById("monthYear");
  const selector = document.getElementById("monthYearSelector");
  if (!monthYearTitle || !selector) return;

  monthYearTitle.addEventListener("click", () => {
    selector.style.display =
      selector.style.display === "block" ? "none" : "block";
  });

  // Fechar se clicar fora
  document.addEventListener("click", (e) => {
    if (!selector.contains(e.target) && e.target !== monthYearTitle) {
      selector.style.display = "none";
    }
  });
}

// Limpa os filtros de busca por data
function clearFilter() {
  selectedDate = null;
  const activeFilter = document.getElementById("activeFilter");
  if (activeFilter) activeFilter.classList.add("hidden");
  renderCalendar();
  renderPosts(allPosts);
}

// 4. O MODO DE PREPARO (Execução quando o HTML estiver pronto)
document.addEventListener("DOMContentLoaded", function () {
  // 1. Rodar renderizações iniciais
  renderCalendar();
  initMonthYearSelector();
  renderPosts(allPosts);

  // 2. Configurar os eventos dos botões de navegação do calendário
  const prevBtn = document.getElementById("prevMonth");
  const nextBtn = document.getElementById("nextMonth");
  const clearBtn = document.getElementById("clearFilter");

  if (prevBtn) {
    prevBtn.addEventListener("click", () => {
      currentDate.setMonth(currentDate.getMonth() - 1);
      renderCalendar();
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener("click", () => {
      currentDate.setMonth(currentDate.getMonth() + 1);
      renderCalendar();
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener("click", clearFilter);
  }

  // console.log("Página de Enfermagem carregada!");
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
