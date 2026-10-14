<?php
// ============================================================
// ScoutGest — Gestão de Elementos
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';

// BLOCO 2: apanhar o texto da pesquisa
$q = trim($_GET['q'] ?? '');

// BLOCO 3: pedir à base de dados os elementos
$stmt = $pdo->prepare(
    'SELECT e.id_elemento, e.nome, e.seccao, t.nome AS tutor
     FROM elementos e
     JOIN tutores t ON e.id_tutor = t.id_tutor
     WHERE e.nome LIKE ?
     ORDER BY e.nome'
);

$stmt->execute(['%' . $q . '%']);
$elementos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// BLOCO 4: o topo da página
$titulo = 'Elementos';
require 'includes/cabecalho.php';
?>

<h1>Elementos</h1>

<!-- Barra de pesquisa e novo elemento -->
<div class="elementos-toolbar">

    <!-- Pesquisa de elementos -->
    <form method="get"
          action="elementos.php"
          class="elementos-pesquisa">

        <input
            type="search"
            name="q"
            class="campo-pesquisa"
            placeholder="Pesquisar por nome"
            aria-label="Pesquisar elementos por nome"
            value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>"
        >

        <button type="submit" class="btn">
            Pesquisar
        </button>

    </form>

    <!-- Adicionar novo elemento -->
    <a href="elemento_novo.php" class="btn">
        + Novo elemento
    </a>

</div>

<?php
// BLOCO 5: mostrar os elementos
?>

<?php if (count($elementos) === 0): ?>

    <p class="vazio">
        Não foi encontrado nenhum elemento.
    </p>

<?php else: ?>

    <div class="cartoes">

        <?php foreach ($elementos as $elemento): ?>

            <article class="cartao">

                <h2>
                    <?= htmlspecialchars($elemento['nome'], ENT_QUOTES, 'UTF-8') ?>
                </h2>

                <p class="meta">
                    Tutor:
                    <?= htmlspecialchars($elemento['tutor'], ENT_QUOTES, 'UTF-8') ?>

                    ·

                    <?= htmlspecialchars($elemento['seccao'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <a href="elemento_ver.php?id=<?= (int) $elemento['id_elemento'] ?>">
                    Ver detalhe →
                </a>

            </article>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php
// BLOCO 6: o fundo da página
require 'includes/rodape.php';
?>