
<?php
// ============================================================
// ScoutGest — Integração Frontend + Backend
// Ficheiro: index.php
// ============================================================

// Iniciar sessão e ligação à base de dados
require 'includes/inicio.php';

// Definir o título da página
$titulo = 'Início';

// Carregar o cabeçalho
require 'includes/cabecalho.php';
?>

<!-- ============================================================
     CARTÃO DE BOAS-VINDAS
     ============================================================ -->

<section class="cartao">

    <h1>Bem-vindo ao ScoutGest</h1>

    <p>
        Aplicação de gestão dos elementos do Agrupamento
        de Escuteiros 676 Cristo-Rei e dos respetivos tutores.
    </p>

    <?php if (isset($_SESSION['id_utilizador'])): ?>

        <!-- Mensagem para utilizadores autenticados -->
        <p>
            Já tens sessão iniciada.
            Podes consultar e gerir os elementos do agrupamento.
        </p>

    <?php else: ?>

        <!-- Mensagem para visitantes -->
        <p>
            Para consultar e gerir os elementos,
            inicia sessão ou cria uma conta.
        </p>

    <?php endif; ?>

</section>

<!-- ============================================================
     BOTÕES DE ACESSO
     ============================================================ -->

<div class="acoes-inicio">

    <?php if (isset($_SESSION['id_utilizador'])): ?>

        <!-- Utilizador autenticado -->
        <a href="elementos.php"
           class="btn-acao btn-entrar">
            Ver os elementos
        </a>

        <a href="conta.php"
           class="btn-acao btn-registar">
            A minha conta
        </a>

    <?php else: ?>

        <!-- Visitante -->
        <a href="login.php"
           class="btn-acao btn-entrar">
            Entrar
        </a>

        <a href="registo.php"
           class="btn-acao btn-registar">
            Criar conta
        </a>

    <?php endif; ?>

</div>

<?php
// ============================================================
// RODAPÉ
// ============================================================

require 'includes/rodape.php';
?>
