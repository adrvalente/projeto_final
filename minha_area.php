<?php
// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';

// BLOCO 2: o dirigente não tem página própria, vai para a gestão
if (($_SESSION['tipo'] ?? '') === 'Dirigente') {
    header('Location: elementos.php');
    exit;
}

// BLOCO 3: descobrir qual é o tutor ligado a esta conta
// (vem da base de dados, nunca do endereço, para ninguém ver os elementos de outros)
$stmt = $pdo->prepare('SELECT id_tutor FROM utilizadores WHERE id_utilizador = ?');
$stmt->execute([$_SESSION['id_utilizador']]);
$idTutor = $stmt->fetchColumn();

$tutor     = null;
$elementos = [];

// BLOCO 4: se a conta tem tutor, ir buscar os dados dele e os seus elementos
if ($idTutor) {
    $stmt = $pdo->prepare('SELECT nome, telefone, email FROM tutores WHERE id_tutor = ?');
    $stmt->execute([$idTutor]);
    $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare(
        'SELECT nome, seccao, data_nascimento
         FROM elementos
         WHERE id_tutor = ?
         ORDER BY nome'
    );
    $stmt->execute([$idTutor]);
    $elementos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// BLOCO 5: o topo da página
$titulo = 'A minha área';
require 'includes/cabecalho.php';
?>
<h1>A minha área</h1>

<?php // BLOCO 6: mostrar o tutor e os elementos ?>
<?php if (!$tutor): ?>
    <p class="vazio">A tua conta ainda não está associada a nenhum tutor. Pede a um dirigente para a associar.</p>
<?php else: ?>
    <section class="detalhe">
        <h2>Tutor</h2>
        <dl>
            <dt>Nome</dt>
            <dd><?= htmlspecialchars($tutor['nome']) ?></dd>
            <dt>Telefone</dt>
            <dd><?= htmlspecialchars($tutor['telefone']) ?></dd>
            <dt>Email</dt>
            <dd><?= htmlspecialchars($tutor['email'] ?? '—') ?></dd>
        </dl>
    </section>

    <h2>Elementos</h2>
    <?php if (count($elementos) === 0): ?>
        <p class="vazio">Ainda não há elementos associados.</p>
    <?php else: ?>
        <div class="cartoes">
            <?php foreach ($elementos as $elemento): ?>
                <article class="cartao">
                    <h2><?= htmlspecialchars($elemento['nome']) ?></h2>
                    <p class="meta">
                        Secção: <?= htmlspecialchars($elemento['seccao']) ?><br>
                        Nascimento: <?= $elemento['data_nascimento'] ? date('d/m/Y', strtotime($elemento['data_nascimento'])) : '—' ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php // BLOCO 7: o fundo da página ?>
<?php require 'includes/rodape.php'; ?>