# Sistema de Gestão CDI (Centro de Dia para Idosos)

Este é um sistema web desenvolvido para gerenciar as atividades, frequências e cadastros de usuários de um Centro de Dia para Idosos. O projeto foca em uma interface limpa, organizada e funcional para facilitar o dia a dia dos profissionais.

## 🚀 Tecnologias Utilizadas

* **PHP 8.x**: Lógica de backend e processamento de dados.
* **Tailwind CSS**: Framework para estilização moderna e responsiva.
* **JavaScript (ES6+)**: Manipulação dinâmica do DOM, calendários e validações.
* **Font Awesome**: Biblioteca de ícones profissionais.
* **MySQL**: Banco de dados para armazenamento das informações (em implementação).

## 📁 Estrutura de Pastas

```text
.
└── Front/
    ├── css/               # Arquivos de estilização (CSS com Variáveis)
    ├── js/                # Lógica de comportamento (Cadastro, Enfermagem, etc.)
    ├── cadastro.php       # Formulário principal de cadastro
    ├── enfermagem.php     # Gestão de atividades de enfermagem
    ├── convencional.php   # Gestão de atividades convencionais
    ├── navegacao.php      # Painel principal de navegação
    └── login.php          # Tela de acesso ao sistema
```

## 🛠️ Funcionalidades Implementadas

[x] Arquitetura Modular: Separação clara entre HTML (PHP), CSS e JS.

[x] Cadastro Inteligente: Cálculo automático de idade e faixa etária.

[x] Interface Responsiva: Menu lateral dinâmico e flexível.

[x] Calendário Interativo: Filtro de postagens por data nas seções de atividade.

[x] Design Consistente: Uso de variáveis CSS para fácil manutenção de cores e temas.

## 🔧 Como Rodar o Projeto

Certifique-se de ter um servidor local instalado (XAMPP, WampServer ou PHP nativo).

Clone este repositório ou baixe os arquivos.

Coloque a pasta do projeto no diretório htdocs (se estiver usando XAMPP).

Acesse no navegador: localhost/nome-da-sua-pasta/Front/navegacao.php.

Ou, acesse o arquivo `projeto-integrado_cdi`:

```bash
cd Front
php -S localhost:8000
```

## 📌 Próximos Passos

[ ] Implementar a conexão real com Banco de Dados MySQL.

[ ] Criar sistema de autenticação (Login/Sessão).

[ ] Gerar relatórios de frequência em PDF.

Desenvolvido com ❤️ para a gestão de cuidados especializados.
