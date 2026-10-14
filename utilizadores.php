<?php
// ScoutGest V2.1 — Gestão de Utilizadores (apenas dirigentes)
require 'includes/inicio.php';
require 'includes/auth.php';
exige_dirigente();

$pesquisa = trim((string) ($_GET['q'] ?? ''));
$sql = 'SELECT u.id_utilizador, u.nome, u.email, u.tipo, u.criado_em, u.id_tutor,
               t.nome AS nome_tutor,
               (SELECT COUNT(*) FROM elementos e WHERE e.id_tutor = u.id_tutor) AS total_elementos
        FROM utilizadores u
        LEFT JOIN tutores t ON t.id_tutor = u.id_tutor';
$parametros = [];
if ($pesquisa !== '') {
    $sql .= ' WHERE u.nome LIKE ? OR u.email LIKE ? OR t.nome LIKE ?';
    $termo = '%' . $pesquisa . '%';
    $parametros = [$termo, $termo, $termo];
}
$sql .= ' ORDER BY u.nome ASC';
$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);
$utilizadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
$titulo = 'Gestão de Utilizadores';
require 'includes/cabecalho.php';
?>
<section class="cartao detalhe gestao-utilizadores">
    <div class="gestao-topo">
        <div><h1><i class="fa-solid fa-users-gear" aria-hidden="true"></i> Gestão de Utilizadores</h1>
            <p>Consulta as contas registadas e associa-as aos tutores do agrupamento.</p></div>
        <span class="gestao-total"><?= count($utilizadores) ?> conta(s)</span>
    </div>
    <form method="get" class="gestao-pesquisa" role="search">
        <label for="q">Pesquisar utilizadores</label>
        <div class="gestao-pesquisa-linha">
            <input type="search" id="q" name="q" value="<?= htmlspecialchars($pesquisa, ENT_QUOTES, 'UTF-8') ?>" placeholder="Nome, email ou tutor associado">
            <button type="submit" class="gestao-botao">Pesquisar</button>
            <?php if ($pesquisa !== ''): ?><a href="utilizadores.php" class="gestao-botao-secundario">Limpar</a><?php endif; ?>
        </div>
    </form>
    <?php if (!$utilizadores): ?>
        <p>Não foram encontradas contas.</p>
    <?php else: ?>
        <div class="gestao-lista">
            <?php foreach ($utilizadores as $u): ?>
                <article class="gestao-item">
                    <div class="gestao-item-dados">
                        <h2><?= htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <p><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="gestao-etiquetas">
                            <span class="gestao-etiqueta"><?= htmlspecialchars($u['tipo'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php if ($u['id_tutor'] !== null): ?>
                                <span class="gestao-etiqueta associado">Tutor #<?= (int) $u['id_tutor'] ?> — <?= htmlspecialchars($u['nome_tutor'] ?? '', ENT_QUOTES, 'UTF-8') ?> (<?= (int) $u['total_elementos'] ?> elementos)</span>
                            <?php else: ?>
                                <span class="gestao-etiqueta pendente">Sem tutor associado</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a class="gestao-botao" href="utilizador_editar.php?id=<?= (int) $u['id_utilizador'] ?>">Gerir conta</a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require 'includes/rodape.php'; ?>
