
<?php
// ============================================================
// ScoutGest — Apagar Conta de Utilizador
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
exige_dirigente();

// BLOCO 2: aceitar apenas pedidos POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: utilizadores.php');
    exit;
}

// BLOCO 3: validar o token CSRF
$token = (string) ($_POST['csrf_token'] ?? '');
$tokenSessao = (string) ($_SESSION['csrf_utilizadores'] ?? '');

if (
    $tokenSessao === '' ||
    !hash_equals($tokenSessao, $token)
) {
    http_response_code(403);
    exit('Pedido inválido. Atualiza a página e tenta novamente.');
}

// BLOCO 4: validar o ID da conta
$id = filter_var(
    $_POST['id_utilizador'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($id === false || $id === null) {
    mensagem('erro', 'Conta inválida.');
    header('Location: utilizadores.php');
    exit;
}

// BLOCO 5: impedir apagar a própria conta
if ($id === (int) $_SESSION['id_utilizador']) {
    mensagem('erro', 'Não podes apagar a tua própria conta.');
    header('Location: utilizadores.php');
    exit;
}

// BLOCO 6: confirmar que a conta existe
$stmt = $pdo->prepare(
    'SELECT id_utilizador
     FROM utilizadores
     WHERE id_utilizador = ?'
);

$stmt->execute([$id]);

if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
    mensagem('erro', 'A conta indicada não existe.');
    header('Location: utilizadores.php');
    exit;
}

// BLOCO 7: apagar a conta
try {

    $stmt = $pdo->prepare(
        'DELETE FROM utilizadores
         WHERE id_utilizador = ?'
    );

    $stmt->execute([$id]);

    // Renovar token CSRF
    $_SESSION['csrf_utilizadores'] = bin2hex(
        random_bytes(32)
    );

    mensagem('sucesso', 'Conta apagada com sucesso.');

} catch (PDOException $e) {

    error_log($e->getMessage());

    mensagem(
        'erro',
        'Não foi possível apagar a conta.'
    );
}

// BLOCO 8: regressar à gestão
header('Location: utilizadores.php');
exit;
