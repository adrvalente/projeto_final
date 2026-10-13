<?php
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

<div class="barra">
    <form method="get" action="elementos.php" class="pesquisa">
        <input type="search" name="q" placeholder="Pesquisar por nome"
               value="<?= htmlspecialchars($q) ?>">
        <button type="submit">Pesquisar</button>
    </form>
    <a href="elemento_novo.php" class="btn">+ Novo elemento</a>
</div>

<?php // BLOCO 5: mostrar os elementos ?>
<?php if (count($elementos) === 0): ?>
    <p class="vazio">Não foi encontrado nenhum elemento.</p>
<?php else: ?>
    <div class="cartoes">
        <?php foreach ($elementos as $elemento): ?>
            <article class="cartao">
                <h2><?= htmlspecialchars($elemento['nome']) ?></h2>
                <p class="meta">
                    Tutor: <?= htmlspecialchars($elemento['tutor']) ?>
                    · <?= htmlspecialchars($elemento['seccao']) ?>
                </p>
                <a href="elemento_ver.php?id=<?= $elemento['id_elemento'] ?>">Ver detalhe →</a>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php // BLOCO 6: o fundo da página ?>
<?php require 'includes/rodape.php'; ?>