/**
 * ARQUIVO: presenca.js (Versão com Gestão de Ocultos Individual e Modal)
 */
const PresencaApp = {
  pacientesAtivos: [],
  pacientesNoGrid: [],
  pacientesOcultos: [],
  date: { month: new Date().getMonth(), year: new Date().getFullYear() },

  async init() {
    await this.carregarPacientes();
    this.render();
    await this.loadData();
    this.bindEvents();
  },

  async carregarPacientes() {
    try {
      const resposta = await fetch("../api/get_cadastrados.php");
      this.pacientesAtivos = await resposta.json();
      
      this.pacientesNoGrid = this.pacientesAtivos.filter(p => p.exibir_na_presenca == 1 || p.exibir_na_presenca === null || p.exibir_na_presenca === undefined);
      this.pacientesOcultos = this.pacientesAtivos.filter(p => p.exibir_na_presenca == 0 && p.exibir_na_presenca !== null);
      
      this.atualizarContadorOcultos();
    } catch (erro) {
      console.error("Erro ao carregar pacientes:", erro);
    }
  },

  atualizarContadorOcultos() {
    const totalOcultos = this.pacientesOcultos.length;
    const btnReset = document.getElementById("reset-btn");
    if (btnReset) {
      if (totalOcultos > 0) {
        btnReset.innerHTML = `<i class="fas fa-eye mr-2"></i> Ver Ocultos (${totalOcultos})`;
        btnReset.classList.remove("bg-red-500", "hover:bg-red-600");
        btnReset.classList.add("bg-gray-600", "hover:bg-gray-700");
      } else {
        btnReset.innerHTML = `<i class="fas fa-eye-slash mr-2"></i> Nenhum Nome Oculto`;
        btnReset.classList.remove("bg-gray-600", "hover:bg-gray-700");
        btnReset.classList.add("bg-red-500", "hover:bg-red-600");
      }
    }
  },

  async loadData() {
    try {
      const resposta = await fetch(`../api/get_presencas.php?mes=${this.date.month + 1}&ano=${this.date.year}`);
      const presencas = await resposta.json();

      document.querySelectorAll(".grid-cell").forEach((c) => {
        c.dataset.value = "0";
        c.textContent = "";
        c.classList.remove("active-mark");
      });

      presencas.forEach((p) => {
        const dia = parseInt(p.data_presenca.split("-")[2]);
        const rowIdx = this.pacientesNoGrid.findIndex(pg => pg.nome === p.nome);

        if (rowIdx !== -1 && p.status == 1) {
          const cell = document.querySelector(`.grid-cell[data-row="${rowIdx}"][data-col="${dia - 1}"]`);
          if (cell) {
            cell.dataset.value = "1";
            cell.textContent = "1";
            cell.classList.add("active-mark");
          }
        }
      });
      this.updateSums();
    } catch (erro) {
      console.error("Erro ao carregar presenças:", erro);
    }
  },

  async toggleCell(cell) {
    const isMarked = cell.dataset.value === "0";
    const novoStatus = isMarked ? 1 : 0;

    cell.dataset.value = novoStatus.toString();
    cell.textContent = isMarked ? "1" : "";
    cell.classList.toggle("active-mark", isMarked);
    this.updateSums(cell.dataset.row, cell.dataset.col);

    const idoso = this.pacientesNoGrid[cell.dataset.row];
    const dia = parseInt(cell.dataset.col) + 1;
    const dataFormatada = `${this.date.year}-${String(this.date.month + 1).padStart(2, "0")}-${String(dia).padStart(2, "0")}`;

    await fetch("../api/salvar_presenca.php", {
      method: "POST",
      body: JSON.stringify({ nome_paciente: idoso.nome, data: dataFormatada, status: novoStatus }),
      headers: { "Content-Type": "application/json" },
    });
  },

  render() {
    this.updateMonthDisplay();
    this.createHeader();
    this.createGrid();
    this.adjustTableSize();
  },

  updateMonthDisplay() {
    const display = document.getElementById("current-month");
    if (display) display.textContent = `${CDIUtils.getMonthNames()[this.date.month]} ${this.date.year}`;
  },

  createHeader() {
    const header = document.getElementById("grid-header");
    if (!header) return;
    let days = CDIUtils.getDaysInMonth(this.date.year, this.date.month);
    let html = `<th class="border border-gray-300 p-2 text-left text-xs font-bold uppercase bg-gray-50">Nome</th>`;
    for (let d = 1; d <= days; d++) {
      let isWeekend = [0, 6].includes(new Date(this.date.year, this.date.month, d).getDay());
      html += `<th class="border border-gray-300 p-1 text-center text-xs ${isWeekend ? "bg-gray-200" : ""}">${d}</th>`;
    }
    html += `<th class="border border-gray-300 p-1 bg-blue-50 text-blue-600 text-xs font-bold">Total</th>`;
    header.innerHTML = `<tr>${html}</tr>`;
  },

  createGrid() {
    const grid = document.getElementById("grid");
    if (!grid) return;
    let days = CDIUtils.getDaysInMonth(this.date.year, this.date.month);
    grid.innerHTML = "";

    this.pacientesNoGrid.forEach((p, i) => {
      let rowHtml = `
                <td class="border border-gray-300 p-2 text-sm whitespace-nowrap font-medium flex justify-between items-center group">
                    <a href="perfil.php?id=${p.id}" class="text-blue-600 hover:underline">
                        ${p.nome}
                    </a>
                    <button onclick="PresencaApp.removerDaLista(${p.id}, '${p.nome}')" class="opacity-0 group-hover:opacity-100 text-red-400 hover:text-red-600 transition p-1">
                        <i class="fas fa-times"></i>
                    </button>
                </td>`;
      for (let d = 0; d < days; d++) {
        let isWeekend = [0, 6].includes(new Date(this.date.year, this.date.month, d + 1).getDay());
        rowHtml += `<td class="grid-cell border border-gray-300 text-center h-10 cursor-pointer ${isWeekend ? "bg-gray-50" : ""}" 
                        data-row="${i}" data-col="${d}" data-value="0" onclick="PresencaApp.toggleCell(this)"></td>`;
      }
      rowHtml += `<td class="border border-gray-300 text-center font-bold text-blue-500" data-row-sum="${i}">0</td>`;
      grid.innerHTML += `<tr>${rowHtml}</tr>`;
    });
    this.addFooterRow(days);
  },

  addFooterRow(days) {
    let footer = `<td class="footer-label border p-2 font-bold text-xs text-right bg-gray-50">TOTAL DIA</td>`;
    for (let d = 0; d < days; d++) footer += `<td class="footer-cell border text-center font-bold text-xs" data-col-sum="${d}">0</td>`;
    footer += `<td class="footer-total border text-center font-bold bg-blue-100 text-blue-900" id="total-sum">0</td>`;
    document.getElementById("grid").innerHTML += `<tr>${footer}</tr>`;
  },

  updateSums(row, col) {
    const getSum = (selector) => Array.from(document.querySelectorAll(selector)).reduce((acc, c) => acc + parseInt(c.dataset.value || 0), 0);
    if (row !== undefined) {
      const el = document.querySelector(`[data-row-sum="${row}"]`);
      if (el) el.textContent = getSum(`[data-row="${row}"]`);
    }
    if (col !== undefined) {
      const el = document.querySelector(`[data-col-sum="${col}"]`);
      if (el) el.textContent = getSum(`[data-col="${col}"]`);
    }
    const totalSum = document.getElementById("total-sum");
    if (totalSum) totalSum.textContent = getSum(".grid-cell");
  },

  adjustTableSize() {
    const table = document.querySelector("table");
    if (table) table.style.minWidth = CDIUtils.getDaysInMonth(this.date.year, this.date.month) * 35 + 200 + "px";
  },

  async removerDaLista(id, nome) {
    if (confirm(`Ocultar ${nome} da lista de presença?`)) await this.toggleExibicao(id, 0);
  },

  async toggleExibicao(id, exibir) {
    const res = await fetch("../api/toggle_presenca_lista.php", {
      method: "POST",
      body: JSON.stringify({ id, exibir }),
      headers: { "Content-Type": "application/json" }
    });
    const data = await res.json();
    if (data.status === "sucesso") await this.init();
  },

  mostrarOcultos() {
    if (this.pacientesOcultos.length === 0) return alert("Nenhum idoso oculto.");
    
    const container = document.getElementById("lista-pacientes-ocultos");
    container.innerHTML = this.pacientesOcultos.map(p => `
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl border border-gray-100">
            <span class="text-gray-700 font-medium">${p.nome}</span>
            <button onclick="PresencaApp.toggleExibicao(${p.id}, 1)" class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-xs font-bold hover:bg-blue-100 transition">
                Restaurar
            </button>
        </div>
    `).join("");
    
    document.getElementById("modal-ocultos").classList.remove("hidden");
  },

  async restaurarTodos() {
    if (confirm("Restaurar todos os idosos ocultos?")) {
        for (let p of this.pacientesOcultos) {
            await fetch("../api/toggle_presenca_lista.php", {
                method: "POST",
                body: JSON.stringify({ id: p.id, exibir: 1 }),
                headers: { "Content-Type": "application/json" }
            });
        }
        document.getElementById("modal-ocultos").classList.add("hidden");
        await this.init();
    }
  },

  bindEvents() {
    document.getElementById("prev-month").onclick = async () => {
      this.date.month--;
      if (this.date.month < 0) { this.date.month = 11; this.date.year--; }
      this.render(); await this.loadData();
    };
    document.getElementById("next-month").onclick = async () => {
      this.date.month++;
      if (this.date.month > 11) { this.date.month = 0; this.date.year++; }
      this.render(); await this.loadData();
    };
    document.getElementById("reset-btn").onclick = () => this.mostrarOcultos();
  },
};

document.addEventListener("DOMContentLoaded", () => PresencaApp.init());
