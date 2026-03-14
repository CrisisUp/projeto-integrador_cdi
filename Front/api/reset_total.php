<?php
require_once __DIR__ . '/../includes/api_auth.php';
require_once __DIR__ . '/../includes/db.php';

try {
    $pdo->beginTransaction();

    // 1. Apaga dados das tabelas dependentes primeiro (Chaves Estrangeiras)
    $pdo->exec("DELETE FROM presenca");
    $pdo->exec("DELETE FROM atividades");
    $pdo->exec("DELETE FROM encaminhamentos");
    
    // 2. Apaga todos os pacientes
    $pdo->exec("DELETE FROM pacientes");

    // 3. Reseta os contadores de ID de todas as tabelas para 1
    $pdo->exec("DELETE FROM sqlite_sequence WHERE name IN ('pacientes', 'presenca', 'atividades', 'encaminhamentos')");

    $pdo->commit();

    // 4. Compacta o banco
    $pdo->exec("VACUUM");

    echo "<h1>☢️ Reset Total Concluído!</h1>";
    echo "<p>O banco de dados está vazio. Todos os pacientes e históricos foram removidos.</p>";
    echo "<a href='debug_db.php'>Voltar para o Debug</a>";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<h1>❌ Erro ao resetar:</h1> " . $e->getMessage();
}
?>
