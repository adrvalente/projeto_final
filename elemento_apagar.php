<?php
// ============================================================
// Apagar Elemento
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
exige_dirigente();

// BLOCO 2: só aceita POST (um link nunca deve apagar dados)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: elementos.php');
    exit;
}

if (!csrf_valido()) {
    mensagem('erro', 'Pedido inválido ou formulário expirado. Tenta novamente.');
    header('Location: elementos.php');
    exit;
}

$idApagar = filter_var($_POST['id_elemento'] ?? null, FILTER_VALIDATE_INT);
if (!$idApagar || $idApagar < 1) {
    mensagem('erro', 'Identificador inválido.');
    header('Location: elementos.php');
    exit;
}

// BLOCO 3: apagar o elemento
$stmt = $pdo->prepare('DELETE FROM elementos WHERE id_elemento = ?');
$stmt->execute([$idApagar]);

// BLOCO 4: guardar a mensagem e voltar à lista
if ($stmt->rowCount() === 1) {
    mensagem('sucesso', 'Elemento apagado.');
} else {
    mensagem('erro', 'O elemento já não existia.');
}
header('Location: elementos.php');
exit;