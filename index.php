<?php
require 'includes/inicio.php';

$titulo = 'Início';
require 'includes/cabecalho.php';
?>
<h1>Bem-vindo ao ScoutGest</h1>
<p>Aplicação de gestão dos elementos do Agrupamento de Escuteiros 676 Cristo-Rei e dos respetivos tutores.</p>
<?php if (isset($_SESSION['id_utilizador'])): ?>
    <p><a href="elementos.php" class="btn">Ver os elementos</a></p>
<?php else: ?>
    <p>Para consultar e gerir os elementos, inicia sessão ou cria uma conta.</p>
    <!-- <p><a href="login.php" class="btn">Entrar</a> <a href="registo.php" class="btn secundario">Criar conta</a></p> -->
<?php endif; ?>
<?php require 'includes/rodape.php'; ?>