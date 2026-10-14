
<?php
// ============================================================
// ScoutGest — A Minha Conta
// Ficheiro: conta.php
// ============================================================

// BLOCO 1: preparar a página (só quem fez login)
require 'includes/inicio.php';
require 'includes/auth.php';

// BLOCO 2: RGPD — direito ao apagamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare(
        'DELETE FROM utilizadores WHERE id_utilizador = ?'
    );

    $stmt->execute([$_SESSION['id_utilizador']]);

    // Terminar a sessão e guardar mensagem
    $_SESSION = [];
    session_destroy();
    session_start();

    mensagem(
        'sucesso',
        'A tua conta e os teus dados pessoais foram apagados.'
    );

    header('Location: index.php');
    exit;
}

// BLOCO 3: RGPD — consultar os dados pessoais
$stmt = $pdo->prepare(
    'SELECT nome, email, tipo, consentimento_em, criado_em
     FROM utilizadores
     WHERE id_utilizador = ?'
);

$stmt->execute([$_SESSION['id_utilizador']]);
$eu = $stmt->fetch(PDO::FETCH_ASSOC);

// BLOCO 4: preparar os dados para apresentação
if (!$eu) {
    header('Location: index.php');
    exit;
}

$nome = htmlspecialchars($eu['nome']);
$email = htmlspecialchars($eu['email']);
$tipo = htmlspecialchars($eu['tipo']);

$dataCriacao = date(
    'd/m/Y',
    strtotime($eu['criado_em'])
);

$dataConsentimento = date(
    'd/m/Y',
    strtotime($eu['consentimento_em'])
);

// BLOCO 5: cabeçalho
$titulo = 'A minha conta';
require 'includes/cabecalho.php';
?>

<!-- ============================================================
     CONTA — CONTEÚDO PRINCIPAL
     ============================================================ -->

<section class="cartao detalhe conta-pagina">

    <!-- CABEÇALHO DA CONTA -->

    <div class="conta-cabecalho">

        <div class="conta-avatar">
            <i class="fa-solid fa-user"></i>
        </div>

        <div>
            <h1>A minha conta</h1>
            <p>
                Consulta os teus dados e gere a
                privacidade da tua conta.
            </p>
        </div>

    </div>

    <!-- ========================================================
         DADOS PESSOAIS
         ======================================================== -->

    <div class="conta-seccao">

        <h2><i class="fa-solid fa-user-tag"></i> Dados pessoais</h2>

        <div class="conta-grelha">

            <div class="conta-campo">
                <span class="conta-label">Nome</span>
                <strong><?= $nome ?></strong>
            </div>

            <div class="conta-campo">
                <span class="conta-label">Email</span>
                <strong><?= $email ?></strong>
            </div>

            <div class="conta-campo">
                <span class="conta-label">Tipo de conta</span>
                <strong><?= $tipo ?></strong>
            </div>

            <div class="conta-campo">
                <span class="conta-label">Data de registo</span>
                <strong><?= $dataCriacao ?></strong>
            </div>

        </div>

    </div>

    <!-- ========================================================
         PRIVACIDADE E RGPD
         ======================================================== -->

    <div class="conta-seccao">

        <h2><i class="fa-solid fa-shield-halved"></i> Privacidade e RGPD</h2>

        <div class="conta-privacidade">

            <span class="conta-label">Consentimento</span>

            <p>
                Aceitaste a política de privacidade em
                <strong><?= $dataConsentimento ?></strong>.
            </p>

            <p class="conta-nota">
                <i class="fas fa-info-circle"></i>
                 A tua palavra-passe é protegida através
                de um hash e não pode ser consultada.
            </p>

        </div>

    </div>

    <!-- ========================================================
         ELIMINAR CONTA
         ======================================================== -->

    <div class="conta-seccao conta-eliminar">

        <h2><i class="fa-solid fa-trash-can"></i> Eliminar conta</h2>

        <p>
            Esta ação é permanente e não pode ser desfeita.
        </p>

        <form
            method="post"
            action="conta.php"
            onsubmit="return confirm(
                'Tens a certeza de que pretendes apagar a tua conta? Esta ação não pode ser desfeita.'
            );"
        >

            <button type="submit" class="btnperigo">
                &#128465; Apagar a minha conta
            </button>

        </form>

    </div>

</section>

<?php
// BLOCO 6: rodapé
require 'includes/rodape.php';
?>
