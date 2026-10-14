<?php
// ============================================================
// ScoutGest — Integração Frontend + Backend
// Ficheiro: includes/cabecalho.php
// ============================================================

// Iniciar sessão (caso ainda não esteja iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Identificar a página atual
$pagina_atual = basename($_SERVER['SCRIPT_NAME'] ?? '');

// Verificar se estamos na página inicial
$pagina_inicial = ($pagina_atual === 'index.php');

// Título da página
$titulo = $titulo ?? 'ScoutGest';
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?> · ScoutGest
    </title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="img/favicon/favicon.ico">

    <!-- CSS principal -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- ============================================================
     CABEÇALHO
     ============================================================ -->

<header class="cabecalho">

    <!-- Logótipo ScoutGest -->
    <div class="logo-container">
        <a href="index.php" aria-label="Página inicial ScoutGest">
            <img src="img/logotipo_transp.png"
                 alt="ScoutGest"
                 class="logotipo">
        </a>
    </div>

    <!--
        O menu de navegação não aparece na página inicial.
        Na página inicial, os botões Entrar e Criar conta
        são apresentados abaixo do cartão principal.
    -->

    <?php if (!$pagina_inicial): ?>

        <nav class="menu-navegacao" aria-label="Navegação principal">

            <?php if (isset($_SESSION['id_utilizador'])): ?>

                <!-- Menu para utilizadores autenticados -->
                <?php if (($_SESSION['tipo'] ?? '') === 'Dirigente'): ?>

                    <a href="elementos.php">Elementos</a>
                    <a href="tutores.php">Tutores</a>

                <?php else: ?>

                    <a href="minha_area.php">A minha área</a>

                <?php endif; ?>

                <a href="conta.php">A minha conta</a>

                <span class="ola">
                    Olá,
                    <?= htmlspecialchars($_SESSION['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </span>

                <a href="logout.php">Sair</a>

            <?php else: ?>

                <!-- Menu para visitantes -->
                <a href="login.php">Entrar</a>
                <a href="registo.php">Criar conta</a>

            <?php endif; ?>

        </nav>

    <?php endif; ?>

</header>

<!-- ============================================================
     CONTEÚDO PRINCIPAL
     ============================================================ -->

<main class="conteudo">

    <!-- Mensagens temporárias de sessão -->
    <?php if (isset($_SESSION['msg']) && is_array($_SESSION['msg'])): ?>

        <?php
        $tipos_validos = ['sucesso', 'erro', 'aviso', 'info'];
        $tipo_mensagem = in_array($_SESSION['msg']['tipo'] ?? '', $tipos_validos, true)
            ? $_SESSION['msg']['tipo']
            : 'info';

        $tipo_mensagem  = htmlspecialchars($tipo_mensagem, ENT_QUOTES, 'UTF-8');
        $texto_mensagem = htmlspecialchars($_SESSION['msg']['texto'] ?? '', ENT_QUOTES, 'UTF-8');
        ?>

        <div class="msg <?= $tipo_mensagem ?>" role="status">
            <?= $texto_mensagem ?>
        </div>

        <?php unset($_SESSION['msg']); ?>

    <?php endif; ?>