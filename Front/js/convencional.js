/**
 * ARQUIVO: convencional.js (Versão com Banco de Dados e CDIUtils)
 */

const ConvencionalApp = {
    currentDate: new Date(),
    selectedDate: null,
    atividades: [],

    async init() {
        await this.carregarAtividades();
        this.renderCalendar();
        this.bindEvents();
    },

    async carregarAtividades(data = null) {
        let url = `../api/get_atividades.php?tipo=convencional`;
        if (data) url += `&data=${data}`;

        try {
            const resposta = await fetch(url);
            this.atividades = await resposta.json();
            this.renderPosts();
        } catch (erro) {
            console.error("Erro ao carregar atividades:", erro);
        }
    },

    async postarAtividade() {
        const textarea = document.querySelector('textarea');
        const conteudo = textarea.value.trim();

        if (!conteudo) return alert("Por favor, descreva a atividade.");

        const btn = document.querySelector('button.bg-green-600');
        btn.disabled = true;
        btn.textContent = "Salvando...";

        try {
            const resposta = await fetch('../api/salvar_atividade.php', {
                method: 'POST',
                body: JSON.stringify({
                    tipo: 'convencional',
                    descricao: conteudo
                }),
                headers: { 'Content-Type': 'application/json' }
            });

            const resultado = await resposta.json();

            if (resultado.status === 'sucesso') {
                textarea.value = "";
                await this.carregarAtividades(); // Recarrega o feed
            } else {
                alert("Erro ao salvar: " + resultado.mensagem);
            }
        } catch (erro) {
            alert("Erro técnico ao salvar atividade.");
        } finally {
            btn.disabled = false;
            btn.textContent = "Postar Atividade";
        }
    },

    renderPosts() {
        const container = document.getElementById("posts-container");
        if (!container) return;
        container.innerHTML = "";

        if (this.atividades.length === 0) {
            container.innerHTML = `<div class="p-8 text-center text-gray-400"><p class="text-lg">Nenhuma atividade registrada.</p></div>`;
            return;
        }

        this.atividades.forEach(post => {
            const postElement = document.createElement("div");
            postElement.className = "border-b border-gray-100 p-6 hover:bg-gray-50/50 transition-colors";
            
            const autor = post.funcionario_nome || "Sistema";

            postElement.innerHTML = `
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-gray-800">${autor}</span>
                            <span class="text-gray-400 text-xs">·</span>
                            <span class="text-gray-400 text-xs">${new Date(post.data_postagem).toLocaleString('pt-BR')}</span>
                        </div>
                        <p class="text-gray-700 leading-relaxed">${post.descricao}</p>
                    </div>
                </div>`;
            container.appendChild(postElement);
        });
    },

    renderCalendar() {
        const monthYear = document.getElementById("monthYear");
        const calendarDays = document.getElementById("calendarDays");
        if (!monthYear || !calendarDays) return;

        const month = this.currentDate.getMonth();
        const year = this.currentDate.getFullYear();
        const monthNames = CDIUtils.getMonthNames();

        monthYear.textContent = `${monthNames[month]} ${year}`;
        calendarDays.innerHTML = "";

        const firstDay = CDIUtils.getFirstDayOfMonth(year, month);
        const daysInMonth = CDIUtils.getDaysInMonth(year, month);

        for (let i = 0; i < firstDay; i++) calendarDays.appendChild(document.createElement("div"));

        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement("div");
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            dayElement.className = `h-8 w-8 flex items-center justify-center rounded-full cursor-pointer text-sm transition-all
                ${this.selectedDate === dateString ? 'bg-green-600 text-white font-bold shadow-md' : 'text-gray-600 hover:bg-green-50'}`;
            dayElement.textContent = day;

            dayElement.onclick = () => {
                if (this.selectedDate === dateString) {
                    this.selectedDate = null;
                    document.getElementById("activeFilter").classList.add("hidden");
                    this.carregarAtividades();
                } else {
                    this.selectedDate = dateString;
                    this.carregarAtividades(dateString);
                    document.getElementById("activeFilter").classList.remove("hidden");
                    document.getElementById("filterDate").textContent = `${day} de ${monthNames[month]}`;
                }
                this.renderCalendar();
            };
            calendarDays.appendChild(dayElement);
        }
    },

    bindEvents() {
        const btnPost = document.querySelector('button.bg-green-600');
        if (btnPost) btnPost.onclick = () => this.postarAtividade();

        document.getElementById("prevMonth").onclick = () => {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.renderCalendar();
        };
        document.getElementById("nextMonth").onclick = () => {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.renderCalendar();
        };
        document.getElementById("clearFilter").onclick = () => {
            this.selectedDate = null;
            document.getElementById("activeFilter").classList.add("hidden");
            this.carregarAtividades();
            this.renderCalendar();
        };
    }
};

document.addEventListener("DOMContentLoaded", () => ConvencionalApp.init());
