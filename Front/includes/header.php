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
    
    <!-- Bibliotecas Globais -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Estilos e Scripts Base do Sistema -->
    <link rel="stylesheet" href="../css/global.css">
    <script src="../js/global.js"></script>
