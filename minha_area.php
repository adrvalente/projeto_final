<?php
// ============================================================
// A minha Família — Página de Consulta do Tutor e Elementos Associados
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';

// BLOCO 2: dirigentes são redirecionados para a gestão
if (($_SESSION['tipo'] ?? '') === 'Dirigente') {
    header('Location: elementos.php');
    exit;
}

// BLOCO 3: obter o tutor associado ao utilizador autenticado
$stmt = $pdo->prepare(
    'SELECT id_tutor FROM utilizadores WHERE id_utilizador = ?'
);
$stmt->execute([$_SESSION['id_utilizador']]);
$idTutor = $stmt->fetchColumn();

$tutor = null;
$elementos = [];

// BLOCO 4: consultar os dados do tutor e os elementos associados
if ($idTutor) {
    $stmt = $pdo->prepare(
        'SELECT nome, telefone, email FROM tutores WHERE id_tutor = ?'
    );
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

// BLOCO 5: cabeçalho
$titulo = 'A minha família';
require 'includes/cabecalho.php';
?>

<!-- CONTEÚDO PRINCIPAL -->
<section class="cartao detalhe conta-pagina">
    <div class="conta-cabecalho">
        <div class="conta-avatar" aria-hidden="true">
            <i class="fa-solid fa-people-roof"></i>
        </div>
        <div>
            <h1>A minha família</h1>
            <p>Consulta os dados do tutor e dos elementos associados à tua família.</p>
        </div>
    </div>

    <?php if (!$tutor): ?>
        <div class="conta-seccao">
            <h2><i class="fa-solid fa-circle-info"></i> Associação familiar</h2>
            <p class="familia-vazio">
                A tua conta ainda não está associada a nenhum tutor.
                Pede a um dirigente para efetuar a associação.
            </p>
        </div>
    <?php else: ?>
        <!-- DADOS DO TUTOR -->
        <div class="conta-seccao">
            <h2><i class="fa-solid fa-user-tag"></i> Dados do tutor</h2>
            <div class="conta-grelha">
                <div class="conta-campo">
                    <span class="conta-label">Nome</span>
                    <strong><?= htmlspecialchars($tutor['nome'], ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="conta-campo">
                    <span class="conta-label">Telefone</span>
                    <strong><?= htmlspecialchars($tutor['telefone'], ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="conta-campo">
                    <span class="conta-label">Email</span>
                    <strong><?= htmlspecialchars($tutor['email'] ?: '—', ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            </div>
        </div>

        <!-- ELEMENTOS ASSOCIADOS -->
        <div class="conta-seccao">
            <h2>
                <i class="fa-solid fa-people-group"></i> Elementos associados
                <span class="familia-contagem">(<?= count($elementos) ?>)</span>
            </h2>
            <?php if (!$elementos): ?>
                <p class="familia-vazio">Ainda não existem elementos associados a este tutor.</p>
            <?php else: ?>
                <div class="conta-grelha">
                    <?php foreach ($elementos as $elemento): ?>
                        <article class="conta-campo familia-elemento">
                            <h3>
                                <i class="fa-solid fa-user" aria-hidden="true"></i>
                                <?= htmlspecialchars($elemento['nome'], ENT_QUOTES, 'UTF-8') ?>
                            </h3>
                            <div class="familia-elemento-dados">
                                <div>
                                    <span class="conta-label">Secção</span>
                                    <strong><?= htmlspecialchars($elemento['seccao'], ENT_QUOTES, 'UTF-8') ?></strong>
                                </div>
                                <div>
                                    <span class="conta-label">Data de nascimento</span>
                                    <strong><?= $elemento['data_nascimento']
                                        ? date('d/m/Y', strtotime($elemento['data_nascimento']))
                                        : '—' ?></strong>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php
// BLOCO 6: rodapé
require 'includes/rodape.php';
?>
