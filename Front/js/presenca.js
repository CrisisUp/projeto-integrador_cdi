/**
 * ARQUIVO: presenca.js (Versão com Banco de Dados e Link para Prontuário)
 */
const PresencaApp = {
    pacientes: [], // Agora armazena {id, nome}
    names: [],     // Array simples de nomes para busca rápida
    rows: 0,
    date: { month: new Date().getMonth(), year: new Date().getFullYear() },

    async init() {
        await this.carregarNomesDoBanco();
        this.render();
        await this.loadData(); // Carrega as presenças do banco
        this.bindEvents();
    },

    async carregarNomesDoBanco() {
        try {
            const resposta = await fetch("../api/get_cadastrados.php");
            this.pacientes = await resposta.json();
            this.names = this.pacientes.map(p => p.nome);
            this.rows = this.pacientes.length;
        } catch (erro) {
            console.error("Erro ao carregar nomes:", erro);
        }
    },

    async loadData() {
        try {
            const resposta = await fetch(`../api/get_presencas.php?mes=${this.date.month + 1}&ano=${this.date.year}`);
            const presencas = await resposta.json();

            document.querySelectorAll(".grid-cell").forEach(c => {
                c.dataset.value = "0";
                c.textContent = "";
                c.classList.remove("active-mark");
            });

            presencas.forEach(p => {
                const dia = parseInt(p.data_presenca.split('-')[2]);
                const rowIdx = this.names.indexOf(p.nome);

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

        const nomeIdoso = this.names[cell.dataset.row];
        const dia = parseInt(cell.dataset.col) + 1;
        const dataFormatada = `${this.date.year}-${String(this.date.month + 1).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;

        try {
            await fetch("../api/salvar_presenca.php", {
                method: 'POST',
                body: JSON.stringify({
                    nome_paciente: nomeIdoso,
                    data: dataFormatada,
                    status: novoStatus
                }),
                headers: { 'Content-Type': 'application/json' }
            });
        } catch (erro) {
            console.error("Erro ao salvar presença:", erro);
        }
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

        let html = `<th class="border p-2 bg-gray-50 text-left text-xs font-bold uppercase">Nome</th>`;
        for (let d = 1; d <= days; d++) {
            let isWeekend = [0, 6].includes(new Date(this.date.year, this.date.month, d).getDay());
            html += `<th class="border p-1 text-center text-xs ${isWeekend ? 'weekend-header' : 'day-header50'}">${d}</th>`;
        }
        html += `<th class="border p-1 day-header text-xs font-bold">Total</th>`;
        header.innerHTML = `<tr>${html}</tr>`;
    },

    createGrid() {
        const grid = document.getElementById("grid");
        if (!grid) return;
        let days = CDIUtils.getDaysInMonth(this.date.year, this.date.month);
        grid.innerHTML = "";

        for (let i = 0; i < this.rows; i++) {
            const p = this.pacientes[i];
            let rowHtml = `
                <td class="border p-2 text-sm bg-white whitespace-nowrap font-medium">
                    <a href="perfil.php?id=${p.id}" class="text-blue-600 hover:underline flex items-center gap-2" title="Ver Prontuário">
                        <i class="fas fa-file-medical text-[10px] opacity-50"></i>
                        ${p.nome}
                    </a>
                </td>`;
            for (let d = 0; d < days; d++) {
                let isWeekend = [0, 6].includes(new Date(this.date.year, this.date.month, d + 1).getDay());
                rowHtml += `<td class="grid-cell border text-center h-10 cursor-pointer ${isWeekend ? 'bg-gray-50' : 'bg-white'}" 
                            data-row="${i}" data-col="${d}" data-value="0" onclick="PresencaApp.toggleCell(this)"></td>`;
            }
            rowHtml += `<td class="border text-center font-bold bg-blue-50 text-blue-800" data-row-sum="${i}">0</td>`;
            grid.innerHTML += `<tr>${rowHtml}</tr>`;
        }
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
        document.getElementById("total-sum").textContent = getSum(".grid-cell");
    },

    adjustTableSize() {
        const table = document.querySelector("table");
        if (table) table.style.minWidth = (CDIUtils.getDaysInMonth(this.date.year, this.date.month) * 35) + 200 + "px";
    },

    bindEvents() {
        document.getElementById("prev-month").onclick = async () => {
            this.date.month--;
            if (this.date.month < 0) { this.date.month = 11; this.date.year--; }
            this.render();
            await this.loadData();
        };
        document.getElementById("next-month").onclick = async () => {
            this.date.month++;
            if (this.date.month > 11) { this.date.month = 0; this.date.year++; }
            this.render();
            await this.loadData();
        };

        const addBtn = document.getElementById("add-person-btn");
        if (addBtn) {
            addBtn.innerHTML = '<i class="fas fa-user-minus mr-2"></i> Remover da Lista';
            addBtn.className = addBtn.className.replace("bg-green-600", "bg-red-500").replace("hover:bg-green-700", "hover:bg-red-600");
            
            addBtn.onclick = async () => {
                const nome = prompt("Digite o nome EXATO do idoso que deseja remover desta lista:");
                if (nome && confirm(`Deseja remover ${nome} da frequência diária?`)) {
                    const res = await fetch("../api/inativar_paciente.php", {
                        method: 'POST',
                        body: JSON.stringify({ nome: nome }),
                        headers: { 'Content-Type': 'application/json' }
                    });
                    const data = await res.json();
                    if (data.status === 'sucesso') { alert("Removido!"); await this.init(); }
                    else { alert(data.mensagem); }
                }
            };
        }
    }
};

document.addEventListener("DOMContentLoaded", () => PresencaApp.init());
