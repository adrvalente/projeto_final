<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> · ScoutGest</title> 
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<header class="topo">
    <a href="index.php" class="marca">ScoutGest</a>
    <nav>
        <?php if (isset($_SESSION['id_utilizador'])): ?>
            <a href="elementos.php">Elementos</a>
            <a href="tutores.php">Tutores</a>
            <a href="conta.php">A minha conta</a>
            <span class="ola">Olá, <?= htmlspecialchars($_SESSION['nome']) ?></span>
            <a href="logout.php">Sair</a>
        <?php else: ?>
            <a href="login.php">Entrar</a>
            <a href="registo.php">Criar conta</a>
        <?php endif; ?>
    </nav>
</header>
<main>
<?php if (isset($_SESSION['msg'])): ?>
    <div class="msg <?= $_SESSION['msg']['tipo'] ?>">
        <?= htmlspecialchars($_SESSION['msg']['texto']) ?>
    </div>
    <?php unset($_SESSION['msg']); // mostra-se uma vez e apaga-se ?>
<?php endif; ?>