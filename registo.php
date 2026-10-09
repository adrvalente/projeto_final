<?php
require 'includes/inicio.php';

if (isset($_SESSION['id_utilizador'])) {
    header('Location: index.php');
    exit;
}

$erros = [];
$nome  = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // 1. Validar no servidor
    if ($nome === '' || mb_strlen($nome) > 100) {
        $erros[] = 'O nome é obrigatório e não pode ter mais de 100 caracteres.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'O email não é válido.';
    }

    if (strlen($password) < 8) {
        $erros[] = 'A password tem de ter pelo menos 8 caracteres.';
    }

    if (!isset($_POST['consentimento'])) {
        $erros[] = 'Tens de aceitar a política de privacidade.';
    }

    // 2. O email não pode estar repetido
    if (!$erros) {
        $stmt = $pdo->prepare('SELECT id_utilizador FROM utilizadores WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erros[] = 'Já existe uma conta com esse email.';
        }
    }

    // 3. Guardar: a password nunca é guardada, só o seu hash
    if (!$erros) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            'INSERT INTO utilizadores (nome, email, password_hash, consentimento_em)
             VALUES (?, ?, ?, NOW())'
        );
        $stmt->execute([$nome, $email, $hash]);

        mensagem('sucesso', 'Conta criada. Já podes iniciar sessão.');
        header('Location: login.php');
        exit;
    }
}

$titulo = 'Criar conta';
require 'includes/cabecalho.php';
?>

<h1>Criar conta</h1>

<?php require 'includes/erros.php'; ?>

<form action="registo.php" method="post" novalidate>
    <p>
        <label for="nome">Nome</label><br>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
    </p>

    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    </p>

    <p>
        <label for="password">Password (mínimo 8 caracteres)</label><br>
        <input type="password" id="password" name="password" required minlength="8">
    </p>

    <p>
        <label>
            <input type="checkbox" name="consentimento" value="1">
            Aceito a política de privacidade.
        </label>
    </p>

    <p>
        <button type="submit" class="btn">Criar conta</button>
        <a href="login.php" class="btn secundario">Já tenho conta</a>
    </p>
</form>

<?php require 'includes/rodape.php'; ?>