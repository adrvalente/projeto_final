<?php
require 'includes/inicio.php';
require 'includes/auth.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    'SELECT e.*, t.nome AS tutor, t.telefone, t.email, t.morada
     FROM elementos e
     JOIN tutores t ON e.id_tutor = t.id_tutor
     WHERE e.id_elemento = ?'
);
$stmt->execute([$id]);
$elemento = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o elemento não existir: código 404 e mensagem própria
if (!$elemento) {
    http_response_code(404);
    $titulo = 'Elemento não encontrado';
    require 'includes/cabecalho.php';
    echo '<h1>Elemento não encontrado</h1><p><a href="elementos.php">Voltar à lista</a></p>';
    require 'includes/rodape.php';
    exit;
}

$titulo = $elemento['nome'];
require 'includes/cabecalho.php';
?>
<section class="detalhe">
    <h1><?= htmlspecialchars($elemento['nome']) ?></h1>
    <dl>
        <dt>Data de nascimento</dt>
        <dd><?= date('d/m/Y', strtotime($elemento['data_nascimento'])) ?></dd>
        <dt>Secção</dt>
        <dd><?= htmlspecialchars($elemento['seccao'] ?? '—') ?></dd>
        <dt>Tutor</dt>
        <dd><?= htmlspecialchars($elemento['tutor']) ?></dd>
        <dt>Telefone do tutor</dt>
        <dd><?= htmlspecialchars($elemento['telefone']) ?></dd>
        <dt>Email do tutor</dt>
        <dd><?= htmlspecialchars($elemento['email'] ?? '—') ?></dd>
        <dt>Morada do tutor</dt>
        <dd><?= htmlspecialchars($elemento['morada'] ?? '—') ?></dd>
    </dl>

    <div class="acoes">
        <a href="elemento_editar.php?id=<?= $elemento['id_elemento'] ?>" class="btn">Editar</a>
        <!-- Apagar altera dados: é um formulário POST, nunca um simples link -->
        <form method="post" action="elemento_apagar.php"
              onsubmit="return confirm('Apagar este elemento?');">
            <input type="hidden" name="id_elemento" value="<?= $elemento['id_elemento'] ?>">
            <button type="submit" class="perigo">Apagar</button>
        </form>
        <a href="elementos.php" class="btn secundario">Voltar</a>
    </div>
</section>
<?php require 'includes/rodape.php'; ?>