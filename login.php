
<?php
// ============================================================
// ScoutGest — Iniciar Sessão
// Ficheiro: login.php
// ============================================================

require 'includes/inicio.php';

// BLOCO 1: verificar se já existe sessão
if (isset($_SESSION['id_utilizador'])) {
    header('Location: index.php');
    exit;
}

// BLOCO 2: valores iniciais
$erros = [];
$email = '';

// BLOCO 3: processar autenticação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Procurar utilizador na base de dados
    $stmt = $pdo->prepare(
        'SELECT id_utilizador, nome, password_hash, tipo
         FROM utilizadores
         WHERE email = ?'
    );

    $stmt->execute([$email]);
    $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar password
    if ($utilizador &&
        password_verify($password, $utilizador['password_hash'])) {

        session_regenerate_id(true);

        $_SESSION['id_utilizador'] = $utilizador['id_utilizador'];
        $_SESSION['nome'] = $utilizador['nome'];
        $_SESSION['tipo'] = $utilizador['tipo'];

        mensagem(
            'sucesso',
            'Sessão iniciada. Bem-vindo, ' . $utilizador['nome'] . '!'
        );

        header('Location: index.php');
        exit;
    }

    $erros[] = 'Email ou password errados.';
}

// BLOCO 4: carregar cabeçalho
$titulo = 'Iniciar sessão';
require 'includes/cabecalho.php';
?>

<!-- ============================================================
     FORMULÁRIO DE AUTENTICAÇÃO
     ============================================================ -->

<section class="login-container">

    <div class="login-card">

        <?php require 'includes/erros.php'; ?>

        <form action="login.php"
              method="post"
              class="login-form">

            <div class="login-campo">
                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="exemplo@email.pt"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="login-campo">
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Introduz a tua password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn login-btn">
                Entrar
            </button>

        </form>

        <div class="login-footer">
            <p>Ainda não tens conta?</p>

            <a href="registo.php"
               class="btn secundario login-btn-criar">
                Criar conta
            </a>
        </div>

    </div>

</section>

<?php require 'includes/rodape.php'; ?>
