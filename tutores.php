<?php
// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
exige_dirigente();

// BLOCO 2: pedir à base de dados os tutores, com o número de elementos de cada um
$stmt = $pdo->prepare(
    'SELECT t.id_tutor, t.nome, t.telefone, t.email, COUNT(e.id_elemento) AS total_elementos
     FROM tutores t
     LEFT JOIN elementos e ON e.id_tutor = t.id_tutor
     GROUP BY t.id_tutor, t.nome, t.telefone, t.email
     ORDER BY t.nome'
);
$stmt->execute();
$tutores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// BLOCO 3: o topo da página
$titulo = 'Tutores';
require 'includes/cabecalho.php';
?>
<h1>Tutores</h1>

<div class="barra">
    <a href="tutor_form.php" class="btn">+ Novo tutor</a>
</div>

<?php // BLOCO 4: mostrar os tutores ?>
<?php if (count($tutores) === 0): ?>
    <p class="vazio">Ainda não há tutores registados.</p>
<?php else: ?>
    <div class="cartoes">
        <?php foreach ($tutores as $tutor): ?>
            <article class="cartao">
                <h2><?= htmlspecialchars($tutor['nome']) ?></h2>
                <p class="meta">
                    Telefone: <?= htmlspecialchars($tutor['telefone']) ?><br>
                    Email: <?= htmlspecialchars($tutor['email'] ?? '—') ?><br>
                    Elementos: <?= $tutor['total_elementos'] ?>
                </p>
                <div class="acoes">
                    <a href="tutor_form.php?id=<?= $tutor['id_tutor'] ?>" class="btn">Editar</a>
                    <form method="post" action="tutor_apagar.php"
                          onsubmit="return confirm('Apagar este tutor?');">
                        <input type="hidden" name="id_tutor" value="<?= $tutor['id_tutor'] ?>">
                        <button type="submit" class="perigo">Apagar</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php // BLOCO 5: o fundo da página ?>
<?php require 'includes/rodape.php'; ?>