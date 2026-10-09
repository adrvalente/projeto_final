<?php
// ScoutGest V1.0.1 — CSS Integração
// Mantém o comportamento condicional da página inicial.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScoutGest — Agrupamento 676 Cristo-Rei</title>
    <link rel="icon" type="image/x-icon" href="img/favicon/favicon.ico">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="cabecalho">
        <img src="img/logotipo_transp.png" alt="ScoutGest" class="logotipo">
    </header>

    <main class="conteudo">
        <section class="cartao">
            <h1>Bem-vindo ao ScoutGest</h1>
            <p>Aplicação de gestão dos elementos do Agrupamento de Escuteiros 676 Cristo-Rei e dos respetivos tutores.</p>
            <?php if (isset($_SESSION['id_utilizador'])): ?>
                <p><a href="elementos.php" class="btn">Ver os elementos</a></p>
            <?php else: ?>
                <p>Para consultar e gerir os elementos, inicia sessão ou cria uma conta.</p>
                <!-- <p><a href="login.php" class="btn">Entrar</a> <a href="registo.php" class="btn secundario">Criar conta</a></p> -->
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
