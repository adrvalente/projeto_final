<?php
// ScoutGest V2.1 — Perfil e associação de uma conta a um tutor
require 'includes/inicio.php';
require 'includes/auth.php';
exige_dirigente();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    mensagem('erro', 'Conta inválida.');
    header('Location: utilizadores.php');
    exit;
}
if (empty($_SESSION['csrf_utilizadores'])) {
    $_SESSION['csrf_utilizadores'] = bin2hex(random_bytes(32));
}
$erros = [];
$stmt = $pdo->prepare('SELECT id_utilizador, nome, email, tipo, id_tutor, criado_em FROM utilizadores WHERE id_utilizador = ?');
$stmt->execute([$id]);
$utilizador = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$utilizador) {
    mensagem('erro', 'A conta indicada não existe.');
    header('Location: utilizadores.php');
    exit;
}
$tutores = $pdo->query('SELECT id_tutor, nome FROM tutores ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string) ($_POST['csrf_token'] ?? '');
    if (!hash_equals($_SESSION['csrf_utilizadores'], $token)) {
        $erros[] = 'O formulário expirou. Atualiza a página e tenta novamente.';
    }
    $tipo = (string) ($_POST['tipo'] ?? '');
    $idTutorPost = (string) ($_POST['id_tutor'] ?? '');
    if (!in_array($tipo, ['Utilizador', 'Dirigente'], true)) {
        $erros[] = 'Seleciona um tipo de conta válido.';
    }
    $idTutor = null;
    if ($idTutorPost !== '') {
        if (!ctype_digit($idTutorPost) || (int) $idTutorPost < 1) {
            $erros[] = 'Seleciona um tutor válido.';
        } else {
            $idTutor = (int) $idTutorPost;
            $idsTutores = array_map('intval', array_column($tutores, 'id_tutor'));
            if (!in_array($idTutor, $idsTutores, true)) {
                $erros[] = 'O tutor selecionado não existe.';
            }
        }
    }
    // Não permitir que o próprio dirigente retire a sua autorização.
    if ($id === (int) $_SESSION['id_utilizador'] && $tipo !== 'Dirigente') {
        $erros[] = 'Não podes retirar as permissões de dirigente da tua própria conta.';
    }
    if (!$erros) {
        $stmt = $pdo->prepare('UPDATE utilizadores SET tipo = ?, id_tutor = ? WHERE id_utilizador = ?');
        $stmt->execute([$tipo, $idTutor, $id]);
        $_SESSION['csrf_utilizadores'] = bin2hex(random_bytes(32));
        mensagem('sucesso', 'Conta atualizada com sucesso.');
        header('Location: utilizadores.php');
        exit;
    }
    $utilizador['tipo'] = $tipo;
    $utilizador['id_tutor'] = $idTutorPost;
}
$titulo = 'Gerir conta';
require 'includes/cabecalho.php';
?>
<section class="cartao detalhe gestao-utilizadores gestao-edicao">
    <div class="gestao-topo"><div>
        <h1><i class="fa-solid fa-user-gear" aria-hidden="true"></i> Gerir conta</h1>
        <p>Altera o perfil e associa esta conta a um tutor existente.</p>
    </div></div>
    <?php if ($erros): ?>
        <div class="gestao-erros" role="alert"><strong>Verifica os seguintes erros:</strong><ul>
            <?php foreach ($erros as $erro): ?><li><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?>
        </ul></div>
    <?php endif; ?>
    <div class="gestao-resumo"><strong><?= htmlspecialchars($utilizador['nome'], ENT_QUOTES, 'UTF-8') ?></strong>
        <span><?= htmlspecialchars($utilizador['email'], ENT_QUOTES, 'UTF-8') ?></span>
        <small>ID da conta: <?= (int) $id ?></small></div>
    <form method="post" action="utilizador_editar.php?id=<?= (int) $id ?>" class="gestao-formulario">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_utilizadores'], ENT_QUOTES, 'UTF-8') ?>">
        <div class="gestao-campo"><label for="tipo">Tipo de conta</label>
            <select id="tipo" name="tipo" required>
                <option value="Utilizador" <?= $utilizador['tipo'] === 'Utilizador' ? 'selected' : '' ?>>Utilizador</option>
                <option value="Dirigente" <?= $utilizador['tipo'] === 'Dirigente' ? 'selected' : '' ?>>Dirigente</option>
            </select>
            <small>O perfil Dirigente tem acesso à gestão de tutores, elementos e contas.</small>
        </div>
        <div class="gestao-campo"><label for="id_tutor">Tutor associado</label>
            <select id="id_tutor" name="id_tutor">
                <option value="">— Sem tutor associado —</option>
                <?php foreach ($tutores as $tutor): ?>
                    <option value="<?= (int) $tutor['id_tutor'] ?>" <?= (string) $utilizador['id_tutor'] === (string) $tutor['id_tutor'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tutor['nome'], ENT_QUOTES, 'UTF-8') ?> (ID <?= (int) $tutor['id_tutor'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <small>A conta de tipo Utilizador poderá consultar os elementos associados a este tutor em «A minha família».</small>
        </div>
        <div class="gestao-acoes">
            <a href="utilizadores.php" class="gestao-botao-secundario">Cancelar</a>
            <button type="submit" class="gestao-botao">Guardar alterações</button>
        </div>
    </form>
</section>
<?php require 'includes/rodape.php'; ?>
