/**
 * ARQUIVO: presenca.js (Refatorado)
 */
const PresencaApp = {
  // Configurações e Estado
  rows: 5,
  date: { month: new Date().getMonth(), year: new Date().getFullYear() },
  names: [
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
  ],

  init() {
    this.render();
    this.bindEvents();
  },

  render() {
    this.updateMonthDisplay();
    this.createHeader();
    this.createGrid();
    this.adjustTableSize();
  },

  updateMonthDisplay() {
    const display = document.getElementById("current-month");
    if (display)
      display.textContent = `${CDIUtils.getMonthNames()[this.date.month]} ${this.date.year}`;
  },

  createHeader() {
    const header = document.getElementById("grid-header");
    if (!header) return;
    let days = CDIUtils.getDaysInMonth(this.date.year, this.date.month);

    let html = `<th class="border p-2 bg-gray-50 text-left text-xs font-bold uppercase">Nome</th>`;
    for (let d = 1; d <= days; d++) {
      let isWeekend = [0, 6].includes(
        new Date(this.date.year, this.date.month, d).getDay(),
      );
      html += `<th class="border p-1 text-center text-xs ${isWeekend ? "bg-gray-200" : "bg-gray-50"}">${d}</th>`;
    }
    html += `<th class="border p-1 bg-blue-50 text-xs font-bold">Total</th>`;
    header.innerHTML = `<tr>${html}</tr>`;
  },

  createGrid() {
    const grid = document.getElementById("grid");
    if (!grid) return;
    let days = CDIUtils.getDaysInMonth(this.date.year, this.date.month);
    grid.innerHTML = "";

    for (let i = 0; i < this.rows; i++) {
      let rowHtml = `<td class="border p-2 text-sm bg-white whitespace-nowrap">${this.names[i] || "Idoso " + (i + 1)}</td>`;
      for (let d = 0; d < days; d++) {
        let isWeekend = [0, 6].includes(
          new Date(this.date.year, this.date.month, d + 1).getDay(),
        );
        rowHtml += `<td class="grid-cell border text-center h-10 cursor-pointer ${isWeekend ? "bg-gray-100" : "bg-white"}" 
                                data-row="${i}" data-col="${d}" data-value="0" onclick="PresencaApp.toggleCell(this)"></td>`;
      }
      rowHtml += `<td class="border text-center font-bold bg-blue-50 text-blue-800" data-row-sum="${i}">0</td>`;
      grid.innerHTML += `<tr>${rowHtml}</tr>`;
    }
    this.addFooterRow(days);
    this.loadData();
  },

  addFooterRow(days) {
    let footer = `<td class="border p-2 font-bold bg-gray-50 text-xs text-right">TOTAL</td>`;
    for (let d = 0; d < days; d++)
      footer += `<td class="border text-center font-bold bg-green-50 text-xs" data-col-sum="${d}">0</td>`;
    footer += `<td class="border text-center font-bold bg-green-100" id="total-sum">0</td>`;
    document.getElementById("grid").innerHTML += `<tr>${footer}</tr>`;
  },

  toggleCell(cell) {
    const themeColor = CDIUtils.getThemeColor();
    const isMarked = cell.dataset.value === "0";

    cell.dataset.value = isMarked ? "1" : "0";
    cell.textContent = isMarked ? "1" : "";
    cell.style.backgroundColor = isMarked ? themeColor : "";
    cell.style.color = isMarked ? "white" : "";

    this.updateSums(cell.dataset.row, cell.dataset.col);
    this.saveData();
  },

  updateSums(row, col) {
    const getSum = (selector) =>
      Array.from(document.querySelectorAll(selector)).reduce(
        (acc, c) => acc + parseInt(c.dataset.value || 0),
        0,
      );

    if (row)
      document.querySelector(`[data-row-sum="${row}"]`).textContent = getSum(
        `[data-row="${row}"]`,
      );
    if (col)
      document.querySelector(`[data-col-sum="${col}"]`).textContent = getSum(
        `[data-col="${col}"]`,
      );
    document.getElementById("total-sum").textContent = getSum(".grid-cell");
  },

  saveData() {
    const active = Array.from(
      document.querySelectorAll(".grid-cell[data-value='1']"),
    ).map((c) => ({ r: c.dataset.row, c: c.dataset.col }));
    localStorage.setItem(
      `presenca_${this.date.year}_${this.date.month}`,
      JSON.stringify(active),
    );
  },

  loadData() {
    const saved = JSON.parse(
      localStorage.getItem(`presenca_${this.date.year}_${this.date.month}`) ||
        "[]",
    );
    saved.forEach((item) => {
      const cell = document.querySelector(
        `[data-row="${item.r}"][data-col="${item.c}"]`,
      );
      if (cell) this.toggleCell(cell);
    });
    this.updateSums();
  },

  adjustTableSize() {
    const container = document.querySelector(".grid-container");
    if (container)
      document.querySelector("table").style.minWidth =
        CDIUtils.getDaysInMonth(this.date.year, this.date.month) * 35 +
        150 +
        "px";
  },

  bindEvents() {
    document.getElementById("prev-month").onclick = () => {
      this.date.month--;
      if (this.date.month < 0) {
        this.date.month = 11;
        this.date.year--;
      }
      this.render();
    };
    document.getElementById("next-month").onclick = () => {
      this.date.month++;
      if (this.date.month > 11) {
        this.date.month = 0;
        this.date.year++;
      }
      this.render();
    };
    document.getElementById("reset-btn").onclick = () => {
      if (confirm("Limpar mês?")) {
        localStorage.removeItem(
          `presenca_${this.date.year}_${this.date.month}`,
        );
        this.render();
      }
    };
    document.getElementById("add-person-btn").onclick = () => {
      this.rows++;
      this.render();
    };
    window.onresize = () => this.adjustTableSize();
  },
};

document.addEventListener("DOMContentLoaded", () => PresencaApp.init());
