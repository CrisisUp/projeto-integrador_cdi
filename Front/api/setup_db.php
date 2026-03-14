<?php
require_once __DIR__ . '/../includes/db.php';

try {
    // 1. Tabela de Pacientes (Idosos)
    $pdo->exec("CREATE TABLE IF NOT EXISTS pacientes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        matricula TEXT UNIQUE,
        nome TEXT NOT NULL,
        sexo TEXT,
        cor_raca TEXT,
        data_nascimento TEXT,
        nis TEXT UNIQUE,
        beneficios TEXT, -- Salvaremos como JSON texto
        lanche_tipo_2 TEXT,
        dieta_especial TEXT,
        status TEXT DEFAULT 'ativo', -- 'ativo' ou 'inativo'
        exibir_na_presenca INTEGER DEFAULT 1, -- 1: Sim, 0: Não
        inativado_por INTEGER,
        inativado_em TIMESTAMP,
        cadastrado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (inativado_por) REFERENCES usuarios(id)
    )");

    // 2. Tabela de Presença (Frequência)
    $pdo->exec("CREATE TABLE IF NOT EXISTS presenca (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        paciente_id INTEGER,
        data_presenca TEXT, -- Ex: '2026-03-09'
        status INTEGER DEFAULT 0, -- 0: Ausente, 1: Presente
        FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
    )");

    // 3. Tabela de Atividades (Feed de Atividades/Enfermagem)
    $pdo->exec("CREATE TABLE IF NOT EXISTS atividades (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        paciente_id INTEGER,
        usuario_id INTEGER,
        tipo TEXT, -- 'convencional' ou 'enfermagem'
        descricao TEXT NOT NULL,
        foto_path TEXT,
        data_postagem TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    )");

    // 4. Tabela de Usuários (Login Seguro)
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        senha TEXT NOT NULL,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 5. Tabela de Encaminhamentos
    $pdo->exec("CREATE TABLE IF NOT EXISTS encaminhamentos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        paciente_id INTEGER,
        data TEXT NOT NULL,
        urgencia TEXT,
        destino TEXT,
        status TEXT DEFAULT 'Pendente',
        usuario_id INTEGER,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    )");

    // 6. Tabela de Logs (Trilha de Auditoria)
    $pdo->exec("CREATE TABLE IF NOT EXISTS logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario_id INTEGER,
        acao TEXT NOT NULL,
        tabela TEXT,
        registro_id INTEGER,
        detalhes TEXT,
        ip_address TEXT,
        data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    )");

    echo "Banco de Dados e Tabelas criados com sucesso em data/clinica.db!";
} catch (PDOException $e) {
    echo "Erro ao criar as tabelas: " . $e->getMessage();
}
?>
