# CDI Digital - Sistema de Gestão de Atividades

Este é um sistema de gestão para Centros de Diagnóstico por Imagem (CDI) ou instituições de saúde, focado no acompanhamento diário de idosos, registros de enfermagem e controle de frequência.

## 🚀 Evolução do Projeto

O projeto passou por uma refatoração completa, evoluindo de um protótipo baseado em arquivos estáticos para um sistema robusto com banco de dados relacional.

### 1. Refatoração de Arquitetura

A estrutura de pastas foi organizada seguindo padrões profissionais de separação de responsabilidades:

- `/pages`: Telas de interface do usuário.
- `/api`: Motores de processamento de dados (PHP).
- `/includes`: Componentes reutilizáveis (Sidebar, Conexão DB, Auth).
- `/data`: Armazenamento persistente protegido.
- `/css` e `/js`: Ativos de estilo e comportamento.

### 2. Segurança e Proteção de Dados

- **Criptografia de Senhas:** Senhas armazenadas via `password_hash` (BCrypt).
- **Proteção de Diretório:** Arquivo `.htaccess` na pasta `/data` impede acesso externo.
- **Auditoria:** Registro automático de quem realizou cada postagem ou remoção.
- **Prevenção contra Injeção:** Uso de **PDO com Prepared Statements**.

### 3. Persistência com Banco de Dados (SQLite)

Migração completa de JSON/TXT para **SQLite**.

- **Integridade:** Relacionamento entre tabelas e restrições de unicidade (Matrícula/NIS).
- **Performance:** Consultas otimizadas e ordenação automática.

---

## 🐳 Como Rodar com Docker (Recomendado)

O projeto está totalmente "dockerizado", o que facilita a instalação em qualquer ambiente sem necessidade de configurar PHP ou Apache manualmente.

### Pré-requisitos

- Docker instalado.
- Docker Compose instalado.

### Passo a Passo

#### 1. Abra o terminal na pasta raiz do projeto

#### 2. Execute o comando

```bash
docker-compose up -d --build
```

#### 3. Acesse no navegador: `http://localhost:8000`

---

## 💻 Tecnologias Utilizadas

- **Frontend:** HTML5, Tailwind CSS, JavaScript (ES6+), FontAwesome, Google Fonts (Inter).
- **Backend:** PHP 8.2 (Apache).
- **Banco de Dados:** SQLite via PDO.
- **Infraestrutura:** Docker & Docker Compose.

## 🛠️ Ferramentas de Gestão (API)

- `api/debug_db.php`: Visualizador administrativo para auditoria dos dados.
- `api/alterar_senha.php`: Permite ao funcionário trocar sua senha de acesso.
- `api/limpar_dinamico.php`: Reseta históricos mantendo os cadastros de idosos.

---
*Projeto desenvolvido como parte do desafio integrador de 2026.*
