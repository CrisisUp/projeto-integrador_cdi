/**
 * ARQUIVO: enfermagem.js - Integrado ao Design System CDI
 */
const EnfermagemApp = {
    selectedDate: new Date().toISOString().split('T')[0],
    currentDate: new Date(),
    monthNames: CDIUtils.getMonthNames(),

    init() {
        this.renderCalendar();
        this.carregarAtividades();
        this.bindEvents();
    },

    async carregarAtividades(data = null) {
        const container = document.getElementById('atividades-container');
        if (!container) return;

        let url = `../api/get_atividades.php?tipo=enfermagem`;
        if (data) url += `&data=${data}`;

        try {
            const resposta = await fetch(url);
            const atividades = await resposta.json();
            this.renderAtividades(atividades);
        } catch (erro) {
            CDIUtils.showToast("Erro ao carregar registros", "danger");
        }
    },

    async salvarAtividade(e) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');

        const dados = {
            paciente_id: form.paciente_id.value,
            tipo: 'enfermagem',
            descricao: form.descricao.value
        };

        if (!dados.paciente_id || !dados.descricao) {
            CDIUtils.showToast("Preencha todos os campos", "warning");
            return;
        }

        btn.disabled = true;
        btn.textContent = "Salvando...";

        try {
            const resposta = await fetch('../api/salvar_atividade.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dados)
            });

            const result = await resposta.json();

            if (result.status === 'sucesso') {
                CDIUtils.showToast("Registro salvo com sucesso!", "success");
                form.reset();
                this.carregarAtividades(this.selectedDate);
            } else {
                CDIUtils.showToast(result.mensagem, "danger");
            }
        } catch (erro) {
            CDIUtils.showToast("Erro de comunicação com o servidor", "danger");
        } finally {
            btn.disabled = false;
            btn.textContent = "Publicar Registro";
        }
    },

    renderAtividades(atividades) {
        const container = document.getElementById('atividades-container');
        container.innerHTML = "";

        if (atividades.length === 0) {
            container.innerHTML = `<div class="p-8 text-center text-gray-400"><p class="cdi-text-lg">Nenhum registro de enfermagem encontrado.</p></div>`;
            return;
        }

        atividades.forEach(post => {
            const postElement = document.createElement('div');
            postElement.className = "bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-4 animate-fade-in";
            
            const autor = post.funcionario_nome || 'Equipe de Enfermagem';
            const paciente = post.paciente_nome || 'Paciente';

            postElement.innerHTML = `
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full cdi-bg-primary-light cdi-text-primary flex items-center justify-center font-bold">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-gray-800 cdi-text-base">${CDIUtils.escapeHTML(autor)}</span>
                            <span class="text-gray-400 cdi-text-xs">${new Date(post.data_postagem).toLocaleString('pt-BR')}</span>
                        </div>
                        <p class="cdi-text-xs cdi-text-primary font-bold uppercase tracking-tighter mb-2">Paciente: ${CDIUtils.escapeHTML(paciente)}</p>
                        <p class="text-gray-700 leading-relaxed cdi-text-base">${CDIUtils.escapeHTML(post.descricao)}</p>
                    </div>
                </div>
            `;
            container.appendChild(postElement);
        });
    },

    renderCalendar() {
        const calendarDays = document.getElementById('calendar-days');
        const monthYear = document.getElementById('month-year');
        if (!calendarDays || !monthYear) return;

        calendarDays.innerHTML = "";
        monthYear.textContent = `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;

        const firstDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1).getDay();
        const daysInMonth = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) {
            calendarDays.appendChild(document.createElement('div'));
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            const dateString = `${this.currentDate.getFullYear()}-${String(this.currentDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            dayElement.className = `h-10 w-10 flex items-center justify-center rounded-xl cursor-pointer transition-all cdi-text-sm
                ${this.selectedDate === dateString ? 'cdi-bg-primary text-white font-bold shadow-md' : 'text-gray-600 hover:cdi-bg-primary-light'}`;
            
            dayElement.textContent = day;
            dayElement.onclick = () => {
                this.selectedDate = dateString;
                this.renderCalendar();
                this.carregarAtividades(dateString);
                
                const filterLabel = document.getElementById("filterDate");
                if (filterLabel) {
                    filterLabel.textContent = `${day} de ${this.monthNames[this.currentDate.getMonth()]}`;
                }
            };
            calendarDays.appendChild(dayElement);
        }
    },

    changeMonth(offset) {
        this.currentDate.setMonth(this.currentDate.getMonth() + offset);
        this.renderCalendar();
    },

    bindEvents() {
        const form = document.getElementById('formEnfermagem');
        if (form) {
            form.addEventListener('submit', (e) => this.salvarAtividade(e));
        }

        document.getElementById('prevMonth')?.addEventListener('click', () => this.changeMonth(-1));
        document.getElementById('nextMonth')?.addEventListener('click', () => this.changeMonth(1));
        
        document.getElementById('btn-hoje')?.addEventListener('click', () => {
            this.currentDate = new Date();
            this.selectedDate = new Date().toISOString().split('T')[0];
            this.renderCalendar();
            this.carregarAtividades(this.selectedDate);
            document.getElementById("filterDate").textContent = "Hoje";
        });
    }
};

document.addEventListener('DOMContentLoaded', () => EnfermagemApp.init());
