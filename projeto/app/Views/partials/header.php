<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal O Editorial</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header class="main-header">
    <div class="container">
        <h1>O Editorial</h1>
        <nav>
    <ul>
        <li><a href="/index.php?url=home">Home</a></li>
        
        <li><a href="/index.php?url=admin/posts">Painel Admin</a></li>
        
        <li><a href="/index.php?url=login">Sair</a></li>
    </ul>
    </nav>
    </div>
</header>

<div class="container feedback-area">
    <?php 
    //session_start(); // Inicia a sessão para ler as mensagens
    if (isset($_SESSION['alerta'])): 
    ?>
        <div class="alert alert-<?= $_SESSION['alerta']['tipo'] ?>">
            <?= $_SESSION['alerta']['mensagem'] ?>
        </div>
        <?php 
            // Limpa a mensagem para não repetir ao atualizar a página
            unset($_SESSION['alerta']); 
        ?>
    <?php endif; ?>
</div>

<main class="container"></main>
