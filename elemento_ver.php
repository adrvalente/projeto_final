<?php
// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
exige_dirigente();

// BLOCO 2: ver qual é o elemento pedido
$id = (int) ($_GET['id'] ?? 0);

// BLOCO 3: pedir esse elemento à base de dados
$stmt = $pdo->prepare(
    'SELECT e.*, t.nome AS tutor, t.telefone, t.email
     FROM elementos e
     JOIN tutores t ON e.id_tutor = t.id_tutor
     WHERE e.id_elemento = ?'
);
$stmt->execute([$id]);
$elemento = $stmt->fetch(PDO::FETCH_ASSOC);

// BLOCO 4: e se o elemento não existir?
if (!$elemento) {
    http_response_code(404);
    $titulo = 'Elemento não encontrado';
    require 'includes/cabecalho.php';
    echo '<h1>Elemento não encontrado</h1><p><a href="elementos.php">Voltar à lista</a></p>';
    require 'includes/rodape.php';
    exit;
}

// BLOCO 5: o topo da página
$titulo = $elemento['nome'];
require 'includes/cabecalho.php';
?>
<section class="detalhe">
    <h1><?= htmlspecialchars($elemento['nome']) ?></h1>
    <?php // BLOCO 6: mostrar os dados ?>
    <dl>
        <dt>Secção</dt>
        <dd><?= htmlspecialchars($elemento['seccao']) ?></dd>
        <dt>Número de censo</dt>
        <dd><?= htmlspecialchars($elemento['numero_censo'] ?? '—') ?></dd>
        <dt>Data de nascimento</dt>
        <dd><?= $elemento['data_nascimento'] ? date('d/m/Y', strtotime($elemento['data_nascimento'])) : '—' ?></dd>
        <dt>Tutor</dt>
        <dd><?= htmlspecialchars($elemento['tutor']) ?></dd>
        <dt>Telefone do tutor</dt>
        <dd><?= htmlspecialchars($elemento['telefone']) ?></dd>
        <dt>Email do tutor</dt>
        <dd><?= htmlspecialchars($elemento['email'] ?? '—') ?></dd>
        <dt>Observações</dt>
        <dd><?= nl2br(htmlspecialchars($elemento['observacoes'] ?? '—')) ?></dd>
    </dl>

    <?php // BLOCO 7: os botões ?>
    <div class="acoes">
        <a href="elemento_editar.php?id=<?= $elemento['id_elemento'] ?>" class="btn">Editar</a>
        <form method="post" action="elemento_apagar.php"
              onsubmit="return confirm('Apagar este elemento?');">
            <input type="hidden" name="id_elemento" value="<?= $elemento['id_elemento'] ?>">
            <button type="submit" class="perigo">Apagar</button>
        </form>
        <a href="elementos.php" class="btn secundario">Voltar</a>
    </div>
</section>
<?php // BLOCO 8: o fundo da página ?>
<?php require 'includes/rodape.php'; ?>