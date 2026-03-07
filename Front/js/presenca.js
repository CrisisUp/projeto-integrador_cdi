document.addEventListener("DOMContentLoaded", function () {
  const rows = 5; // Número inicial de pessoas
  const grid = document.getElementById("grid");
  const gridHeader = document.getElementById("grid-header");
  const currentMonthDisplay = document.getElementById("current-month");

  // Variáveis para controle do mês
  let currentDate = new Date();
  let currentMonth = currentDate.getMonth();
  let currentYear = currentDate.getFullYear();

  // Nomes de exemplo
  const sampleNames = [
    "Ana Silva",
    "Carlos Oliveira",
    "Mariana Santos",
    "Pedro Costa",
    "Juliana Lima",
    "Roberto Alves",
    "Fernanda Dias",
    "Lucas Mendes",
    "Camila Rocha",
    "Bruno Gomes",
  ];

  // Atualizar o display do mês atual
  function updateMonthDisplay() {
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
    currentMonthDisplay.textContent = `${monthNames[currentMonth]} ${currentYear}`;
  }

  // Obter o número de dias no mês
  function getDaysInMonth(year, month) {
    return new Date(year, month + 1, 0).getDate();
  }

  // Criar o cabeçalho
  function createHeader() {
    gridHeader.innerHTML = "";
    const headerRow = document.createElement("tr");

    // Célula para o título da coluna de nomes
    const nameHeader = document.createElement("th");
    nameHeader.className =
      "header-cell name-header border border-gray-300 p-1 text-left name-column";
    nameHeader.textContent = "Nome";
    headerRow.appendChild(nameHeader);

    // Criar cabeçalhos para os dias do mês
    const daysInMonth = getDaysInMonth(currentYear, currentMonth);

    for (let day = 1; day <= daysInMonth; day++) {
      const date = new Date(currentYear, currentMonth, day);
      const dayOfWeek = date.getDay(); // 0 = Domingo, 6 = Sábado
      const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

      const dayHeader = document.createElement("th");
      dayHeader.className = `header-cell border border-gray-300 p-1 text-center day-cell ${isWeekend ? "weekend" : ""}`;

      // Formato mais compacto para o cabeçalho
      dayHeader.innerHTML = `<div>${day}</div><div class="text-xs">${dayOfWeek === 0 ? "D" : dayOfWeek === 6 ? "S" : ""}</div>`;
      dayHeader.dataset.day = day - 1;
      headerRow.appendChild(dayHeader);
    }

    // Célula para o total
    const totalHeader = document.createElement("th");
    totalHeader.className =
      "header-cell border border-gray-300 p-1 text-center bg-blue-100 total-column";
    totalHeader.textContent = "Total";
    headerRow.appendChild(totalHeader);

    gridHeader.appendChild(headerRow);
  }

  // Criar o grid
  function createGrid() {
    grid.innerHTML = "";
    const daysInMonth = getDaysInMonth(currentYear, currentMonth);

    // Criar linhas e células
    for (let i = 0; i < rows; i++) {
      const row = document.createElement("tr");

      // Célula de nome
      const nameCell = document.createElement("td");
      nameCell.className = "name-cell border border-gray-300 p-1 name-column";
      nameCell.textContent = sampleNames[i] || `Pessoa ${i + 1}`;
      row.appendChild(nameCell);

      // Células para os dias
      for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(currentYear, currentMonth, day);
        const dayOfWeek = date.getDay(); // 0 = Domingo, 6 = Sábado
        const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

        const cell = document.createElement("td");
        cell.className = `grid-cell border border-gray-300 p-1 text-center h-8 day-cell cursor-pointer ${isWeekend ? "weekend" : "bg-white"}`;
        cell.dataset.row = i;
        cell.dataset.col = day - 1;
        cell.dataset.value = "0";
        cell.addEventListener("click", toggleCell);
        row.appendChild(cell);
      }

      // Célula de soma da linha
      const rowSum = document.createElement("td");
      rowSum.className =
        "sum-cell border border-gray-300 p-1 text-center h-8 bg-blue-100 total-column";
      rowSum.textContent = "0";
      rowSum.dataset.rowSum = i;
      row.appendChild(rowSum);

      grid.appendChild(row);
    }

    // Criar linha para somas das colunas
    const sumRow = document.createElement("tr");

    // Célula de texto "Total"
    const totalLabel = document.createElement("td");
    totalLabel.className =
      "name-cell border border-gray-300 p-1 text-left font-medium name-column";
    totalLabel.textContent = "Total";
    sumRow.appendChild(totalLabel);

    // Células de soma para cada coluna
    for (let day = 1; day <= daysInMonth; day++) {
      const date = new Date(currentYear, currentMonth, day);
      const dayOfWeek = date.getDay();
      const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

      const colSum = document.createElement("td");
      colSum.className = `sum-cell border border-gray-300 p-1 text-center h-8 day-cell ${isWeekend ? "bg-green-200" : "bg-green-100"}`;
      colSum.textContent = "0";
      colSum.dataset.colSum = day - 1;
      sumRow.appendChild(colSum);
    }

    // Célula vazia no canto inferior direito
    const emptyCell = document.createElement("td");
    emptyCell.className =
      "border border-gray-300 p-1 text-center h-8 bg-gray-100 font-bold total-column";
    emptyCell.id = "total-sum";
    emptyCell.textContent = "0";
    sumRow.appendChild(emptyCell);

    grid.appendChild(sumRow);

    updateAllSums();

    // Adicionar linhas vazias para preencher o espaço disponível
    addEmptyRowsToFill();
  }

  // Adicionar linhas vazias para preencher o espaço disponível
  function addEmptyRowsToFill() {
    const gridContainer = document.querySelector(".grid-container");
    const tableHeight = document.querySelector("table").offsetHeight;
    const containerHeight = gridContainer.clientHeight;

    // Se a tabela for menor que o container, adicionar linhas vazias
    if (tableHeight < containerHeight) {
      const currentRows = document.querySelectorAll("#grid tr").length - 1; // -1 para a linha de soma
      const rowHeight = 32; // Altura aproximada de uma linha em pixels
      const extraSpace = containerHeight - tableHeight;
      const rowsToAdd = Math.floor(extraSpace / rowHeight);

      // Adicionar linhas vazias
      const daysInMonth = getDaysInMonth(currentYear, currentMonth);
      const sumRow = document.querySelector("#grid tr:last-child");

      for (let i = 0; i < rowsToAdd && currentRows + i < 10; i++) {
        const row = document.createElement("tr");

        // Célula de nome
        const nameCell = document.createElement("td");
        nameCell.className = "name-cell border border-gray-300 p-1 name-column";
        nameCell.textContent =
          sampleNames[currentRows + i] || `Pessoa ${currentRows + i + 1}`;
        row.appendChild(nameCell);

        // Células para os dias
        for (let day = 1; day <= daysInMonth; day++) {
          const date = new Date(currentYear, currentMonth, day);
          const dayOfWeek = date.getDay();
          const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

          const cell = document.createElement("td");
          cell.className = `grid-cell border border-gray-300 p-1 text-center h-8 day-cell cursor-pointer ${isWeekend ? "weekend" : "bg-white"}`;
          cell.dataset.row = currentRows + i;
          cell.dataset.col = day - 1;
          cell.dataset.value = "0";
          cell.addEventListener("click", toggleCell);
          row.appendChild(cell);
        }

        // Célula de soma da linha
        const rowSum = document.createElement("td");
        rowSum.className =
          "sum-cell border border-gray-300 p-1 text-center h-8 bg-blue-100 total-column";
        rowSum.textContent = "0";
        rowSum.dataset.rowSum = currentRows + i;
        row.appendChild(rowSum);

        // Inserir antes da linha de soma
        grid.insertBefore(row, sumRow);
      }

      updateAllSums();
    }
  }

  // Alternar valor da célula
  function toggleCell() {
    const currentValue = parseInt(this.dataset.value);
    const newValue = currentValue === 0 ? 1 : 0;

    this.dataset.value = newValue;
    this.textContent = newValue === 0 ? "" : newValue;

    const isWeekend = this.classList.contains("weekend");

    if (newValue === 1) {
      if (isWeekend) {
        this.classList.add("active");
      } else {
        this.classList.add("bg-blue-50");
      }
    } else {
      if (isWeekend) {
        this.classList.remove("active");
      } else {
        this.classList.remove("bg-blue-50");
      }
    }

    updateSums(parseInt(this.dataset.row), parseInt(this.dataset.col));
  }

  // Atualizar somas para uma célula específica
  function updateSums(row, col) {
    updateRowSum(row);
    updateColSum(col);
    updateTotalSum();
  }

  // Atualizar soma da linha
  function updateRowSum(row) {
    let sum = 0;
    const cells = document.querySelectorAll(`td[data-row="${row}"]`);

    cells.forEach((cell) => {
      sum += parseInt(cell.dataset.value || 0);
    });

    const rowSumCell = document.querySelector(`td[data-row-sum="${row}"]`);
    rowSumCell.textContent = sum;
  }

  // Atualizar soma da coluna
  function updateColSum(col) {
    let sum = 0;
    const cells = document.querySelectorAll(`td[data-col="${col}"]`);

    cells.forEach((cell) => {
      sum += parseInt(cell.dataset.value || 0);
    });

    const colSumCell = document.querySelector(`td[data-col-sum="${col}"]`);
    colSumCell.textContent = sum;
  }

  // Atualizar soma total
  function updateTotalSum() {
    let sum = 0;
    const cells = document.querySelectorAll(".grid-cell");

    cells.forEach((cell) => {
      sum += parseInt(cell.dataset.value || 0);
    });

    const totalSumCell = document.getElementById("total-sum");
    totalSumCell.textContent = sum;
  }

  // Atualizar todas as somas
  function updateAllSums() {
    const currentRows = document.querySelectorAll("#grid tr").length - 1; // -1 para a linha de soma

    for (let i = 0; i < currentRows; i++) {
      updateRowSum(i);
    }

    const daysInMonth = getDaysInMonth(currentYear, currentMonth);
    for (let day = 1; day <= daysInMonth; day++) {
      updateColSum(day - 1);
    }

    updateTotalSum();
  }

  // Limpar o grid
  document.getElementById("reset-btn").addEventListener("click", function () {
    const cells = document.querySelectorAll(".grid-cell");
    cells.forEach((cell) => {
      cell.dataset.value = "0";
      cell.textContent = "";

      if (cell.classList.contains("weekend")) {
        cell.classList.remove("active");
      } else {
        cell.classList.remove("bg-blue-50");
      }
    });

    updateAllSums();
  });

  // Adicionar pessoa
  document
    .getElementById("add-person-btn")
    .addEventListener("click", function () {
      const currentRows = document.querySelectorAll("#grid tr").length - 1; // -1 para a linha de soma
      if (currentRows >= 10) {
        alert("Máximo de 10 pessoas atingido!");
        return;
      }

      const row = document.createElement("tr");
      const daysInMonth = getDaysInMonth(currentYear, currentMonth);

      // Célula de nome
      const nameCell = document.createElement("td");
      nameCell.className = "name-cell border border-gray-300 p-1 name-column";
      nameCell.textContent =
        sampleNames[currentRows] || `Pessoa ${currentRows + 1}`;
      row.appendChild(nameCell);

      // Células para os dias
      for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(currentYear, currentMonth, day);
        const dayOfWeek = date.getDay();
        const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

        const cell = document.createElement("td");
        cell.className = `grid-cell border border-gray-300 p-1 text-center h-8 day-cell cursor-pointer ${isWeekend ? "weekend" : "bg-white"}`;
        cell.dataset.row = currentRows;
        cell.dataset.col = day - 1;
        cell.dataset.value = "0";
        cell.addEventListener("click", toggleCell);
        row.appendChild(cell);
      }

      // Célula de soma da linha
      const rowSum = document.createElement("td");
      rowSum.className =
        "sum-cell border border-gray-300 p-1 text-center h-8 bg-blue-100 total-column";
      rowSum.textContent = "0";
      rowSum.dataset.rowSum = currentRows;
      row.appendChild(rowSum);

      // Inserir antes da linha de soma
      const sumRow = document.querySelector("#grid tr:last-child");
      grid.insertBefore(row, sumRow);

      updateAllSums();
    });

  // Navegação entre meses
  document.getElementById("prev-month").addEventListener("click", function () {
    currentMonth--;
    if (currentMonth < 0) {
      currentMonth = 11;
      currentYear--;
    }
    updateMonthDisplay();
    createHeader();
    createGrid();
  });

  document.getElementById("next-month").addEventListener("click", function () {
    currentMonth++;
    if (currentMonth > 11) {
      currentMonth = 0;
      currentYear++;
    }
    updateMonthDisplay();
    createHeader();
    createGrid();
  });

  // Ajustar tamanho da tabela para caber na tela
  function adjustTableSize() {
    const container = document.querySelector(".grid-container");
    const daysInMonth = getDaysInMonth(currentYear, currentMonth);

    // Calcular largura disponível (menos margens e paddings)
    const availableWidth = container.clientWidth - 20; // 20px para margens

    // Largura necessária para nome e total
    const fixedWidth = 8 + 3; // 8rem para nome + 3rem para total

    // Largura disponível para células de dias
    const availableForDays = availableWidth - fixedWidth * 16; // convertendo rem para px aproximadamente

    // Largura ideal para cada célula de dia
    const idealDayWidth = Math.floor(availableForDays / daysInMonth);

    // Atualizar CSS se necessário
    if (idealDayWidth > 0) {
      const style = document.createElement("style");
      style.textContent = `.day-cell { width: ${idealDayWidth}px; min-width: ${idealDayWidth}px; max-width: ${idealDayWidth}px; }`;
      document.head.appendChild(style);
    }

    // Verificar se precisamos adicionar linhas vazias
    setTimeout(addEmptyRowsToFill, 100);
  }

  // Inicializar
  updateMonthDisplay();
  createHeader();
  createGrid();

  // Ajustar tamanho quando a janela for redimensionada
  window.addEventListener("resize", adjustTableSize);
  // Ajustar tamanho inicial
  setTimeout(adjustTableSize, 100);
});

/*

(function () {
  function c() {
    var b = a.contentDocument || a.contentWindow.document;
    if (b) {
      var d = b.createElement("script");
      d.innerHTML =
        "window.__CF$cv$params={r:'93bcf18d92471b21',t:'MTc0NjU4MTMzNy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
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
