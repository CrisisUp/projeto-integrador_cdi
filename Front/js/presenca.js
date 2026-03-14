/**
 * ARQUIVO: js/presenca.js - Integrado ao Design System CDI
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

      // Filtra quem deve aparecer no grid
      this.pacientesNoGrid = this.pacientesAtivos.filter(
        (p) => p.exibir_na_presenca != 0,
      );
      this.pacientesOcultos = this.pacientesAtivos.filter(
        (p) => p.exibir_na_presenca == 0,
      );

      this.atualizarContadorOcultos();
    } catch (erro) {
      CDIUtils.showToast("Erro ao carregar lista de idosos", "danger");
    }
  },

  atualizarContadorOcultos() {
    const btnReset = document.getElementById("reset-btn");
    if (!btnReset) return;

    const total = this.pacientesOcultos.length;
    if (total > 0) {
      btnReset.innerHTML = `<i class="fas fa-eye mr-2"></i> Ocultos (${total})`;
      btnReset.className =
        "cdi-btn cdi-bg-accent-light cdi-text-accent px-4 py-2 rounded-xl transition font-bold";
    } else {
      btnReset.innerHTML = `<i class="fas fa-eye-slash mr-2"></i> Nenhum Oculto`;
      btnReset.className =
        "cdi-btn cdi-bg-muted cdi-text-gray px-4 py-2 rounded-xl opacity-50 cursor-default";
    }
  },

  async loadData() {
    try {
      const resposta = await fetch(
        `../api/get_presencas.php?mes=${this.date.month + 1}&ano=${this.date.year}`,
      );
      const presencas = await resposta.json();

      // Limpa o grid antes de preencher
      document.querySelectorAll(".grid-cell").forEach((c) => {
        c.dataset.value = "0";
        c.textContent = "";
        c.classList.remove("active-mark");
      });

      presencas.forEach((p) => {
        const dia = parseInt(p.data_presenca.split("-")[2]);
        // Identificação por ID em vez de Nome para evitar duplicidade
        const rowIdx = this.pacientesNoGrid.findIndex(
          (pg) => pg.id == p.paciente_id,
        );

        if (rowIdx !== -1 && p.status == 1) {
          const cell = document.querySelector(
            `.grid-cell[data-row="${rowIdx}"][data-col="${dia - 1}"]`,
          );
          if (cell) {
            cell.dataset.value = "1";
            cell.textContent = "1";
            cell.classList.add("active-mark");
          }
        }
      });
      this.updateSums();
    } catch (erro) {
      CDIUtils.showToast("Erro ao carregar presenças do mês", "danger");
    }
  },

  async toggleCell(cell) {
    const idoso = this.pacientesNoGrid[cell.dataset.row];
    if (!idoso) return;

    const isMarked = cell.dataset.value === "0";
    const novoStatus = isMarked ? 1 : 0;

    // UI Feedback imediato (Optimistic UI)
    cell.dataset.value = novoStatus.toString();
    cell.textContent = isMarked ? "1" : "";
    cell.classList.toggle("active-mark", isMarked);
    this.updateSums(cell.dataset.row, cell.dataset.col);

    const dia = parseInt(cell.dataset.col) + 1;
    const dataFormatada = `${this.date.year}-${String(this.date.month + 1).padStart(2, "0")}-${String(dia).padStart(2, "0")}`;

    try {
      await fetch("../api/salvar_presenca.php", {
        method: "POST",
        body: JSON.stringify({
          paciente_id: idoso.id,
          data: dataFormatada,
          status: novoStatus,
        }),
        headers: CDIUtils.getHeaders(),
      });
    } catch (e) {
      CDIUtils.showToast("Erro ao salvar no servidor", "danger");
    }
  },

  render() {
    this.updateMonthDisplay();
    this.createHeader();
    this.createGrid();
  },

  updateMonthDisplay() {
    const display = document.getElementById("current-month");
    if (display)
      display.textContent = `${CDIUtils.getMonthNames()[this.date.month]} / ${this.date.year}`;
  },

  createHeader() {
    const header = document.getElementById("grid-header");
    if (!header) return;
    let days = CDIUtils.getDaysInMonth(this.date.year, this.date.month);
    let html = `<th class="cdi-border presenca-th-idoso rounded-tl-xl">Idoso</th>`;

    for (let d = 1; d <= days; d++) {
      let dateObj = new Date(this.date.year, this.date.month, d);
      let isWeekend = [0, 6].includes(dateObj.getDay());
      html += `<th class="cdi-border p-1 text-center cdi-text-xs ${isWeekend ? "cdi-bg-muted opacity-60" : "grid-header-cell"}">${d}</th>`;
    }
    html += `<th class="cdi-border presenca-th-total rounded-tr-xl">Total</th>`;
    header.innerHTML = `<tr>${html}</tr>`;
  },

  createGrid() {
    const grid = document.getElementById("grid");
    if (!grid) return;
    let days = CDIUtils.getDaysInMonth(this.date.year, this.date.month);
    grid.innerHTML = "";

    this.pacientesNoGrid.forEach((p, i) => {
      let rowHtml = `
        <td class="cdi-border p-2 cdi-text-sm whitespace-nowrap font-medium flex justify-between items-center group bg-white dark:bg-gray-800">
            <a href="perfil.php?id=${p.id}" class="cdi-text-primary hover:underline font-bold">
                ${CDIUtils.escapeHTML(p.nome)}
            </a>
            <button onclick="PresencaApp.removerDaLista(${p.id}, '${CDIUtils.escapeHTML(p.nome)}')" class="opacity-0 group-hover:opacity-100 cdi-text-danger hover:scale-110 transition-all p-1">
                <i class="fas fa-eye-slash"></i>
            </button>
        </td>`;

      for (let d = 0; d < days; d++) {
        let isWeekend = [0, 6].includes(
          new Date(this.date.year, this.date.month, d + 1).getDay(),
        );
        rowHtml += `<td class="grid-cell cdi-border text-center h-10 cursor-pointer ${isWeekend ? "cdi-bg-muted opacity-30" : "hover:bg-gray-100 dark:hover:bg-gray-700"}" 
                        data-row="${i}" data-col="${d}" data-value="0" onclick="PresencaApp.toggleCell(this)"></td>`;
      }
      rowHtml += `<td class="cdi-border presenca-row-total" data-row-sum="${i}">0</td>`;
      grid.innerHTML += `<tr>${rowHtml}</tr>`;
    });
    this.addFooterRow(days);
  },

  addFooterRow(days) {
    let footer = `<td class="cdi-border presenca-footer-label rounded-bl-xl">PRESENÇAS POR DIA</td>`;
    for (let d = 0; d < days; d++) {
      footer += `<td class="cdi-border presenca-footer-sum" data-col-sum="${d}">0</td>`;
    }
    footer += `<td class="cdi-border rounded-br-xl" id="total-sum">0</td>`;
    document.getElementById("grid").innerHTML += `<tr>${footer}</tr>`;
  },

  updateSums(row, col) {
    const getSum = (selector) =>
      Array.from(document.querySelectorAll(selector)).reduce(
        (acc, c) => acc + parseInt(c.dataset.value || 0),
        0,
      );

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

  async removerDaLista(id, nome) {
    if (
      confirm(
        `Ocultar ${nome} desta lista? (Ele continuará cadastrado no sistema)`,
      )
    ) {
      await this.toggleExibicao(id, 0);
      CDIUtils.showToast(`${nome} ocultado com sucesso`, "info");
    }
  },

  async toggleExibicao(id, exibir) {
    try {
      const res = await fetch("../api/toggle_presenca_lista.php", {
        method: "POST",
        body: JSON.stringify({ id, exibir }),
        headers: CDIUtils.getHeaders(),
      });
      const data = await res.json();
      if (data.status === "sucesso") await this.init();
    } catch (e) {
      CDIUtils.showToast("Erro ao processar solicitação", "danger");
    }
  },

  mostrarOcultos() {
    if (this.pacientesOcultos.length === 0) return;

    const container = document.getElementById("lista-pacientes-ocultos");
    container.innerHTML = this.pacientesOcultos
      .map(
        (p) => `
        <div class="flex items-center justify-between p-3 cdi-bg-muted rounded-xl mb-2">
            <span class="cdi-text-gray font-medium cdi-text-sm">${p.nome}</span>
            <button onclick="PresencaApp.toggleExibicao(${p.id}, 1)" class="cdi-bg-primary cdi-text-white px-3 py-1 rounded-lg cdi-text-xs font-bold hover:opacity-80 transition">
                Restaurar
            </button>
        </div>
    `,
      )
      .join("");

    document.getElementById("modal-ocultos").classList.remove("hidden");
    document.getElementById("modal-ocultos").classList.add("flex"); // Garante que o modal centralize se usar flex
  },

  bindEvents() {
    document.getElementById("prev-month").onclick = async () => {
      this.date.month--;
      if (this.date.month < 0) {
        this.date.month = 11;
        this.date.year--;
      }
      this.render();
      await this.loadData();
    };
    document.getElementById("next-month").onclick = async () => {
      this.date.month++;
      if (this.date.month > 11) {
        this.date.month = 0;
        this.date.year++;
      }
      this.render();
      await this.loadData();
    };
    document.getElementById("reset-btn").onclick = () => this.mostrarOcultos();

    // Fechar modal ao clicar fora ou no X (se houver)
    window.onclick = (event) => {
      const modal = document.getElementById("modal-ocultos");
      if (event.target == modal) modal.classList.add("hidden");
    };
  },
};

document.addEventListener("DOMContentLoaded", () => PresencaApp.init());
