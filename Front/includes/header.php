<?php
/**
 * header.php - Cabeçalho unificado do CDI Digital
 * Contém verificação de sessão, metadados e bibliotecas globais.
 */
session_start();

// 1. Verificação de Segurança
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bibliotecas Globais -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Estilos e Scripts Base do Sistema -->
    <link rel="stylesheet" href="../css/global.css">
    <script>
        // Define o Token CSRF para ser usado em todas as chamadas de API via JS
        window.csrfToken = "<?php echo $_SESSION['csrf_token'] ?? ''; ?>";
    </script>
    <script src="../js/global.js"></script>
