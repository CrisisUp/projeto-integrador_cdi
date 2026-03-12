/**
 * ARQUIVO: enfermagem.js (Versão com Banco de Dados)
 * DESCRIÇÃO: Gerencia o calendário e as postagens da página de enfermagem.
 */

const EnfermagemApp = {
    currentDate: new Date(),
    selectedDate: null,
    atividades: [],
    monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],

    async init() {
        await this.carregarAtividades();
        this.renderCalendar();
        this.bindEvents();
    },

    async carregarAtividades(data = null) {
        let url = `../api/get_atividades.php?tipo=enfermagem`;
        if (data) url += `&data=${data}`;

        try {
            const resposta = await fetch(url);
            this.atividades = await resposta.json();
            this.renderPosts();
        } catch (erro) {
            console.error("Erro ao carregar atividades:", erro);
        }
    },

    async registrarEvolucao() {
        const textarea = document.querySelector('textarea');
        const conteudo = textarea.value.trim();

        if (!conteudo) return alert("Por favor, descreva a evolução ou intercorrência.");

        const btn = document.querySelector('button.cdi-bg-primary');
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = "Salvando...";

        try {
            const resposta = await fetch('../api/salvar_atividade.php', {
                method: 'POST',
                body: JSON.stringify({
                    tipo: 'enfermagem',
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
            alert("Erro técnico ao salvar evolução.");
        } finally {
            btn.disabled = false;
            btn.textContent = originalText;
        }
    },

    renderPosts() {
        const container = document.getElementById("posts-container");
        if (!container) return;
        container.innerHTML = "";

        if (this.atividades.length === 0) {
            container.innerHTML = `<div class="p-8 text-center text-gray-400"><p class="cdi-text-lg">Nenhum registro de enfermagem encontrado.</p></div>`;
            return;
        }

        this.atividades.forEach(post => {
            const postElement = document.createElement("div");
            postElement.className = "border-b border-gray-200 p-6 hover:cdi-bg-primary-light transition-colors";
            
            const autor = post.funcionario_nome || "Equipe Enfermagem";

            postElement.innerHTML = `
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full cdi-bg-primary-light flex items-center justify-center cdi-text-primary">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-gray-800 cdi-text-base">${autor}</span>
                            <span class="text-gray-400 cdi-text-xs">·</span>
                            <span class="text-gray-400 cdi-text-xs">${new Date(post.data_postagem).toLocaleString('pt-BR')}</span>
                        </div>
                        <p class="text-gray-700 leading-relaxed cdi-text-base">${post.descricao}</p>
                    </div>
                </div>`;
            container.appendChild(postElement);
        });
    },

    renderCalendar() {
        const monthYear = document.getElementById("monthYear");
        const calendarDays = document.getElementById("calendarDays");
        if (!monthYear || !calendarDays) return;

        monthYear.textContent = `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
        calendarDays.innerHTML = "";

        const firstDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1).getDay();
        const daysInMonth = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) calendarDays.appendChild(document.createElement("div"));

        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement("div");
            const dateString = `${this.currentDate.getFullYear()}-${String(this.currentDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            dayElement.className = `h-8 w-8 flex items-center justify-center rounded-full cursor-pointer cdi-text-sm transition-all
                ${this.selectedDate === dateString ? 'cdi-bg-primary text-white font-bold shadow-md' : 'text-gray-600 hover:cdi-bg-primary-light'}`;
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
                    document.getElementById("filterDate").textContent = `${day} de ${this.monthNames[this.currentDate.getMonth()]}`;
                }
                this.renderCalendar();
            };
            calendarDays.appendChild(dayElement);
        }
    },

    bindEvents() {
        const btnPost = document.querySelector('button.cdi-bg-primary');
        if (btnPost) btnPost.onclick = () => this.registrarEvolucao();

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

document.addEventListener("DOMContentLoaded", () => EnfermagemApp.init());
