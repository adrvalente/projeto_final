<?php
require 'includes/inicio.php';

// Se já tiver sessão iniciada, redireciona para a página inicial
if (isset($_SESSION['id_utilizador'])) {
    header('Location: index.php');
    exit;
}

$erros = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // 1. Procurar o utilizador pelo email
    $stmt = $pdo->prepare('SELECT id_utilizador, nome, password_hash, tipo FROM utilizadores WHERE email = ?');
    $stmt->execute([$email]);
    $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Comparar a password escrita com o hash guardado
    if ($utilizador && password_verify($password, $utilizador['password_hash'])) {
        session_regenerate_id(true);   // novo id de sessão depois do login (segurança contra fixation)
        $_SESSION['id_utilizador'] = $utilizador['id_utilizador'];
        $_SESSION['nome']          = $utilizador['nome'];
        $_SESSION['tipo']          = $utilizador['tipo'];

        mensagem('sucesso', 'Sessão iniciada. Bem-vindo, ' . $utilizador['nome'] . '!');
        header('Location: index.php');
        exit;
    }

    // Mensagem igual nos dois casos: não se revela se o email existe
    $erros[] = 'Email ou password errados.';
}

$titulo = 'Iniciar sessão';
require 'includes/cabecalho.php';
?>

<h1>Iniciar sessão</h1>

<?php require 'includes/erros.php'; ?>

<form action="login.php" method="post" novalidate>
    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    </p>

    <p>
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required>
    </p>

    <p>
        <button type="submit" class="btn">Entrar</button>
        <a href="registo.php" class="btn secundario">Criar conta</a>
    </p>
</form>

<?php require 'includes/rodape.php'; ?>