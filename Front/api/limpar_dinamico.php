<?php
require_once __DIR__ . '/../includes/db.php';

try {
    // Inicia uma transação para garantir que ou apaga tudo ou nada
    $pdo->beginTransaction();

    // 1. Apaga os registros de frequência
    $pdo->exec("DELETE FROM presenca");
    
    // 2. Apaga o feed de atividades (Convencional e Enfermagem)
    $pdo->exec("DELETE FROM atividades");

    // 3. Reseta os contadores de ID dessas tabelas
    $pdo->exec("DELETE FROM sqlite_sequence WHERE name IN ('presenca', 'atividades')");

    $pdo->commit();

    // 4. Compacta o banco para liberar espaço em disco
    $pdo->exec("VACUUM");

    echo "<h1>✅ Limpeza Dinâmica Concluída!</h1>";
    echo "<p>Presenças e Atividades foram apagadas. Os pacientes continuam cadastrados.</p>";
    echo "<a href='debug_db.php'>Voltar para o Debug</a>";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<h1>❌ Erro ao limpar:</h1> " . $e->getMessage();
}
?>
