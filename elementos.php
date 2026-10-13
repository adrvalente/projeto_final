<?php
require 'includes/inicio.php';
require 'includes/auth.php';

// Pesquisa: o texto vem do URL (?q=...), por isso lê-se em $_GET
$q = trim($_GET['q'] ?? '');

// JOIN: junta a cada elemento o nome do respetivo tutor
$stmt = $pdo->prepare(
    'SELECT e.id_elemento, e.nome, e.seccao, t.nome AS tutor
     FROM elementos e
     JOIN tutores t ON e.id_tutor = t.id_tutor
     WHERE e.nome LIKE ?
     ORDER BY e.nome'
);
$stmt->execute(['%' . $q . '%']);
$elementos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

<?php if (count($elementos) === 0): ?>
    <p class="vazio">Não foi encontrado nenhum elemento.</p>
<?php else: ?>
    <div class="cartoes">
        <?php foreach ($elementos as $elemento): ?>
            <article class="cartao">
                <h2><?= htmlspecialchars($elemento['nome']) ?></h2>
                <p class="meta">
                    Tutor: <?= htmlspecialchars($elemento['tutor']) ?>
                    <?php if ($elemento['seccao']): ?> · <?= htmlspecialchars($elemento['seccao']) ?><?php endif; ?>
                </p>
                <a href="elemento_ver.php?id=<?= $elemento['id_elemento'] ?>">Ver detalhe →</a>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require 'includes/rodape.php'; ?>