<?php
require_once __DIR__ . '/../includes/db.php';

echo "<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <title>Debug Banco de Dados - CDI</title>
    <link href='https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css' rel='stylesheet'>
    <style>
        body { background: #f3f4f6; padding: 2rem; font-family: sans-serif; }
        .card { background: white; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.5rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-size: 0.875rem; }
        th { background: #f9fafb; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; }
        .tag { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
        .tag-blue { background: #dbeafe; color: #1e40af; }
        .tag-green { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>

    <h1 class='text-3xl font-bold mb-8 text-gray-800'>🔍 Inspeção de Banco de Dados (SQLite)</h1>

    <!-- 1. TABELA DE PACIENTES -->
    <div class='card'>
        <h2>Pacientes Cadastrados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    <th>NIS</th>
                    <th>Data Nasc.</th>
                    <th>Cadastrado em</th>
                </tr>
            </thead>
            <tbody>";
            $pacientes = $pdo->query("SELECT * FROM pacientes ORDER BY nome ASC")->fetchAll();
            foreach($pacientes as $p) {
                echo "<tr>
                    <td>{$p['id']}</td>
                    <td class='font-bold text-blue-600'>{$p['nome']}</td>
                    <td>" . ($p['matricula'] ?? '---') . "</td>
                    <td>" . ($p['nis'] ?? '---') . "</td>
                    <td>{$p['data_nascimento']}</td>
                    <td class='text-gray-400'>{$p['cadastrado_em']}</td>
                </tr>";
            }
echo "      </tbody>
        </table>
    </div>

    <!-- 2. TABELA DE ATIVIDADES -->
    <div class='card'>
        <h2>Últimas Atividades / Evoluções</h2>
        <table>
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>";
            $atividades = $pdo->query("SELECT * FROM atividades ORDER BY data_postagem DESC LIMIT 10")->fetchAll();
            foreach($atividades as $a) {
                $tagClass = $a['tipo'] === 'enfermagem' ? 'tag-blue' : 'tag-green';
                echo "<tr>
                    <td class='whitespace-nowrap'>{$a['data_postagem']}</td>
                    <td><span class='tag {$tagClass}'>" . strtoupper($a['tipo']) . "</span></td>
                    <td class='text-gray-700'>{$a['descricao']}</td>
                </tr>";
            }
echo "      </tbody>
        </table>
    </div>

    <!-- 3. TABELA DE PRESENÇA -->
    <div class='card'>
        <h2>Registros de Presença (Frequência)</h2>
        <table>
            <thead>
                <tr>
                    <th>Paciente (ID)</th>
                    <th>Data</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>";
            $presencas = $pdo->query("SELECT p.nome, pr.data_presenca, pr.status 
                                     FROM presenca pr 
                                     JOIN pacientes p ON p.id = pr.paciente_id 
                                     ORDER BY pr.data_presenca DESC LIMIT 15")->fetchAll();
            foreach($presencas as $pr) {
                $status = $pr['status'] == 1 ? '✅ PRESENTE' : '❌ AUSENTE';
                echo "<tr>
                    <td class='font-bold'>{$pr['nome']}</td>
                    <td>{$pr['data_presenca']}</td>
                    <td class='" . ($pr['status'] == 1 ? 'text-green-600' : 'text-red-600') . " font-bold'>{$status}</td>
                </tr>";
            }
echo "      </tbody>
        </table>
    </div>

    <div class='text-center text-gray-400 text-sm'>
        Caminho do Banco: <code>data/clinica.db</code><br>
        Gerado automaticamente pelo Assistente Gemini
    </div>

</body>
</html>";
?>
