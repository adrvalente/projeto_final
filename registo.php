
<?php
// ============================================================
// Criar Conta
// ============================================================

require 'includes/inicio.php';

// BLOCO 1: verificar se já existe sessão
if (isset($_SESSION['id_utilizador'])) {
    header('Location: index.php');
    exit;
}

// BLOCO 2: valores iniciais
$erros = [];
$nome  = '';
$email = '';

// BLOCO 3: processar o registo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validar os dados no servidor
    if ($nome === '' || mb_strlen($nome) > 100) {
        $erros[] = 'O nome é obrigatório e não pode ter mais de 100 caracteres.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
        $erros[] = 'O email não é válido.';
    }

    if (strlen($password) < 8) {
        $erros[] = 'A password tem de ter pelo menos 8 caracteres.';
    }

    if (!isset($_POST['consentimento'])) {
        $erros[] = 'Tens de aceitar a política de privacidade.';
    }

    // Verificar se o email já existe
    if (!$erros) {
        $stmt = $pdo->prepare(
            'SELECT id_utilizador FROM utilizadores WHERE email = ?'
        );
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $erros[] = 'Já existe uma conta com esse email.';
        }
    }

    // Criar a conta
    if (!$erros) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO utilizadores
             (nome, email, password_hash, consentimento_em)
             VALUES (?, ?, ?, NOW())'
        );

        $stmt->execute([$nome, $email, $hash]);

        mensagem('sucesso', 'Conta criada. Já podes iniciar sessão.');
        header('Location: login.php');
        exit;
    }
}

// BLOCO 4: apresentar a página
$titulo = 'Criar conta';
require 'includes/cabecalho.php';
?>

<!-- ============================================================
     CARTÃO DE REGISTO
     ============================================================ -->

<main class="pagina-autenticacao">

    <section class="cartao cartao-autenticacao">

        <div class="autenticacao-cabecalho">
            <h1>Criar conta</h1>
            <p>
                Preenche os dados para criares a tua conta
                no ScoutGest.
            </p>
        </div>

        <?php require 'includes/erros.php'; ?>

        <form
            action="registo.php"
            method="post"
            id="form-principal"
            class="formulario"
            novalidate
        >

            <!-- Nome -->
            <div class="campo-formulario">
                <label for="nome">Nome completo</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    maxlength="100"
                    autocomplete="name"
                    value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Introduz o teu nome"
                    required
                >
            </div>

            <!-- Email -->
            <div class="campo-formulario">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    maxlength="150"
                    autocomplete="email"
                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Introduz o teu email"
                    required
                >
            </div>

            <!-- Password -->
            <div class="campo-formulario">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Mínimo de 8 caracteres"
                    required
                >
            </div>

            <!-- Consentimento -->
            <div class="campo-consentimento">
                <label for="consentimento">
                    <input
                        type="checkbox"
                        id="consentimento"
                        name="consentimento"
                        value="1"
                        required
                        <?= isset($_POST['consentimento']) ? 'checked' : '' ?>
                    >
                    <span>
                        Li e aceito a
                        <a href="privacidade.php" target="_blank" rel="noopener noreferrer">
                            política de privacidade
                        </a>.
                    </span>
                </label>
            </div>

            <!-- Botões -->
            <div class="autenticacao-acoes">
                <button type="submit" class="btn">
                    Criar conta
                </button>

                <a href="login.php" class="btn secundario">
                    Já tenho conta
                </a>
            </div>

        </form>

    </section>

</main>

<?php require 'includes/rodape.php'; ?>
