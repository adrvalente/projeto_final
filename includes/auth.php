
<?php
// ============================================================
// Controlo de Acesso
// ============================================================

// BLOCO 1: verificar se existe uma sessão autenticada
if (!isset($_SESSION['id_utilizador'])) {
    mensagem('erro', 'Tens de iniciar sessão para ver essa página.');
    header('Location: login.php');
    exit;
}

// BLOCO 2: verificar se o utilizador continua a existir
// e atualizar o perfil com os dados da base de dados
$stmt = $pdo->prepare(
    'SELECT tipo
     FROM utilizadores
     WHERE id_utilizador = ?'
);

$stmt->execute([$_SESSION['id_utilizador']]);
$tipoAtual = $stmt->fetchColumn();

if ($tipoAtual === false) {
    $_SESSION = [];
    session_destroy();

    header('Location: login.php');
    exit;
}

// Atualizar o perfil na sessão
$_SESSION['tipo'] = $tipoAtual;

// BLOCO 3: restringir páginas aos dirigentes
function exige_dirigente()
{
    if (($_SESSION['tipo'] ?? '') !== 'Dirigente') {
        mensagem('erro', 'Não tens permissão para aceder a essa página.');
        header('Location: index.php');
        exit;
    }
}
