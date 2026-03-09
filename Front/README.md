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

- **Proteção de Diretório:** Implementado arquivo `.htaccess` na pasta `/data` para impedir o acesso externo a dados sensíveis.
- **Prevenção contra Injeção:** Migração de consultas simples para **PDO com Prepared Statements**, protegendo o sistema contra ataques de SQL Injection.
- **Controle de Sessão:** Sistema de login com validação de credenciais e proteção de rotas.

### 3. Persistência com Banco de Dados (SQLite)

O sistema abandonou o uso de arquivos `JSON` e `TXT` para escrita, migrando para o **SQLite**.

- **Integridade:** Relacionamento entre tabelas (Pacientes, Atividades e Presença).
- **Performance:** Consultas otimizadas e ordenação alfabética automática.
- **Escalabilidade:** Preparado para gerenciar o fluxo de até 800 idosos anuais com segurança.

## 🛠️ Ferramentas de Gestão (API)

Foram criados scripts utilitários para facilitar a manutenção do banco:

- `api/setup_db.php`: Inicializa o banco e cria as tabelas.
- `api/migrar_json_para_sql.php`: Migra dados legados do JSON para o banco de dados.
- `api/debug_db.php`: Visualizador administrativo para inspeção rápida dos dados.
- `api/limpar_dinamico.php`: Reseta históricos mantendo os cadastros de idosos.
- `api/reset_total.php`: Limpa completamente o sistema para novos testes.

## 💻 Tecnologias Utilizadas

- **Frontend:** HTML5, Tailwind CSS, JavaScript (ES6+), FontAwesome.
- **Backend:** PHP 8.x.
- **Banco de Dados:** SQLite via PDO.

---
*Projeto desenvolvido como parte do desafio integrador de 2026.*
