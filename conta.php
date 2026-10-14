<?php
// BLOCO 1: preparar a página (só quem fez login)
require 'includes/inicio.php';
require 'includes/auth.php';

// BLOCO 2: RGPD, direito ao apagamento (só corre se o botão foi carregado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM utilizadores WHERE id_utilizador = ?');
    $stmt->execute([$_SESSION['id_utilizador']]);

    // terminar a sessão e começar uma nova só para guardar a mensagem
    $_SESSION = [];
    session_destroy();
    session_start();
    mensagem('sucesso', 'A tua conta e os teus dados pessoais foram apagados.');
    header('Location: index.php');
    exit;
}

// BLOCO 3: RGPD, direito de acesso (ir buscar os dados guardados sobre esta pessoa)
$stmt = $pdo->prepare(
    'SELECT nome, email, tipo, consentimento_em, criado_em
     FROM utilizadores
     WHERE id_utilizador = ?'
);
$stmt->execute([$_SESSION['id_utilizador']]);
$eu = $stmt->fetch(PDO::FETCH_ASSOC);

// BLOCO 4: o topo da página
$titulo = 'A minha conta';
require 'includes/cabecalho.php';
?>
<section class="detalhe">
    <h1>A minha conta</h1>
    <p>Estes são todos os dados pessoais que a aplicação guarda sobre ti:</p>

    <?php // BLOCO 5: mostrar os dados ?>
    <dl>
        <dt>Nome</dt>             <dd><?= htmlspecialchars($eu['nome']) ?></dd>
        <dt>Email</dt>            <dd><?= htmlspecialchars($eu['email']) ?></dd>
        <dt>Tipo de conta</dt>    <dd><?= htmlspecialchars($eu['tipo']) ?></dd>
        <dt>Conta criada em</dt>  <dd><?= $eu['criado_em'] ?></dd>
        <dt>Consentimento</dt>    <dd>Aceitaste a política de privacidade em <?= $eu['consentimento_em'] ?></dd>
    </dl>
    <p>A password não aparece porque não é guardada: guardamos apenas o seu hash.</p>

    <?php // BLOCO 6: o botão de apagar (um formulário POST, com confirmação) ?>
    <form method="post" action="conta.php"
          onsubmit="return confirm('Apagar a conta? Esta ação não pode ser desfeita.');">
        <button type="submit" class="perigo">Apagar a minha conta</button>
    </form>
</section>
<?php // BLOCO 7: o fundo da página ?>
<?php require 'includes/rodape.php'; ?>