
<?php
// ============================================================
// ScoutGest — Integração Frontend + Backend
// Ficheiro: includes/cabecalho.php
// ============================================================

// Garantir que a sessão está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Identificar a página atual
$pagina_atual = basename($_SERVER['SCRIPT_NAME'] ?? '');
$pagina_inicial = ($pagina_atual === 'index.php');

// Título da página
$titulo = $titulo ?? 'ScoutGest';
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <script src="https://kit.fontawesome.com/7a6b0262fa.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?> · ScoutGest</title>
    <link rel="icon" type="image/x-icon" href="img/favicon/favicon.ico">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header class="cabecalho">

    <div class="logo-container">
        <a href="index.php" aria-label="Página inicial ScoutGest">
            <img src="img/logotipo_transp.png" alt="ScoutGest" class="logotipo">
        </a>
    </div>

    <?php if (!$pagina_inicial): ?>

        <button type="button"
                class="menu-toggle"
                id="menu-toggle"
                aria-label="Abrir menu"
                aria-controls="menu-principal"
                aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="menu-navegacao"
             id="menu-principal"
             aria-label="Navegação principal">

            <?php if (isset($_SESSION['id_utilizador'])): ?>

                <?php if (($_SESSION['tipo'] ?? '') === 'Dirigente'): ?>

                    <a href="elementos.php">Elementos</a>
                    <a href="tutores.php">Tutores</a>

                <?php else: ?>

                    <a href="minha_area.php">A minha família</a>

                <?php endif; ?>

                <a href="conta.php">A minha conta</a>

                <span class="ola">
                    Olá, <?= htmlspecialchars($_SESSION['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </span>

                <a href="logout.php">Sair</a>

            <?php else: ?>

                <a href="login.php">Entrar</a>
                <a href="registo.php">Criar conta</a>

            <?php endif; ?>

        </nav>

    <?php endif; ?>

</header>

<main class="conteudo">

    <?php if (isset($_SESSION['msg']) && is_array($_SESSION['msg'])): ?>
        <?php
        $tipos_validos = ['sucesso', 'erro', 'aviso', 'info'];

        $tipo_mensagem = in_array($_SESSION['msg']['tipo'] ?? '', $tipos_validos, true)
            ? $_SESSION['msg']['tipo']
            : 'info';

        $tipo_mensagem = htmlspecialchars($tipo_mensagem, ENT_QUOTES, 'UTF-8');
        $texto_mensagem = htmlspecialchars($_SESSION['msg']['texto'] ?? '', ENT_QUOTES, 'UTF-8');
        ?>

        <div class="msg <?= $tipo_mensagem ?>" role="status">
            <?= $texto_mensagem ?>
        </div>

        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const botao = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu-principal');

    if (!botao || !menu) return;

    function fecharMenu() {
        menu.classList.remove('ativo');
        botao.classList.remove('ativo');
        botao.setAttribute('aria-expanded', 'false');
        botao.setAttribute('aria-label', 'Abrir menu');
    }

    botao.addEventListener('click', function () {
        const aberto = menu.classList.toggle('ativo');
        botao.classList.toggle('ativo', aberto);
        botao.setAttribute('aria-expanded', String(aberto));
        botao.setAttribute('aria-label', aberto ? 'Fechar menu' : 'Abrir menu');
    });

    menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', fecharMenu);
    });

    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape') fecharMenu();
    });

    document.addEventListener('click', function (evento) {
        if (!botao.contains(evento.target) && !menu.contains(evento.target)) {
            fecharMenu();
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 700) fecharMenu();
    });
});
</script>
