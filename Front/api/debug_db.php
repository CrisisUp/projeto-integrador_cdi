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
        .tag-red { background: #fee2e2; color: #991b1b; }
        .tag-green { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>

    <h1 class='text-3xl font-bold mb-8 text-gray-800'>🔍 Inspeção de Banco de Dados (Auditoria)</h1>

    <!-- 1. TABELA DE PACIENTES -->
    <div class='card'>
        <h2>Lista de Pacientes (Todos os Status)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Status</th>
                    <th>Removido por</th>
                    <th>Data Remoção</th>
                </tr>
            </thead>
            <tbody>";
            $sql = "SELECT p.*, u.nome as removido_por_nome 
                    FROM pacientes p 
                    LEFT JOIN usuarios u ON p.inativado_por = u.id 
                    ORDER BY p.status ASC, p.nome ASC";
            $pacientes = $pdo->query($sql)->fetchAll();
            foreach($pacientes as $p) {
                $statusTag = $p['status'] === 'ativo' ? 'tag-green' : 'tag-red';
                echo "<tr>
                    <td>{$p['id']}</td>
                    <td class='font-bold'>{$p['nome']}</td>
                    <td><span class='tag {$statusTag}'>" . strtoupper($p['status']) . "</span></td>
                    <td>" . ($p['removido_por_nome'] ?? '---') . "</td>
                    <td class='text-gray-400'>" . ($p['inativado_em'] ?? '---') . "</td>
                </tr>";
            }
echo "      </tbody>
        </table>
    </div>

    <!-- 2. TABELA DE ATIVIDADES -->
    <div class='card'>
        <h2>Auditoria de Postagens</h2>
        <table>
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Autor</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>";
            $atividades = $pdo->query("SELECT a.*, u.nome as funcionario 
                                     FROM atividades a 
                                     LEFT JOIN usuarios u ON a.usuario_id = u.id 
                                     ORDER BY a.data_postagem DESC LIMIT 10")->fetchAll();
            foreach($atividades as $a) {
                echo "<tr>
                    <td class='whitespace-nowrap'>{$a['data_postagem']}</td>
                    <td class='font-bold text-blue-600'>" . ($a['funcionario'] ?? 'Sistema') . "</td>
                    <td class='text-gray-700'>{$a['descricao']}</td>
                </tr>";
            }
echo "      </tbody>
        </table>
    </div>

    <div class='text-center text-gray-400 text-sm'>
        Caminho do Banco: <code>data/clinica.db</code>
    </div>

</body>
</html>";
?>
