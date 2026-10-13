<?php
// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';

// BLOCO 2: só aceita POST (um link nunca deve apagar dados)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: elementos.php');
    exit;
}

// BLOCO 3: apagar o elemento
$stmt = $pdo->prepare('DELETE FROM elementos WHERE id_elemento = ?');
$stmt->execute([(int) ($_POST['id_elemento'] ?? 0)]);

// BLOCO 4: guardar a mensagem e voltar à lista
if ($stmt->rowCount() === 1) {
    mensagem('sucesso', 'Elemento apagado.');
} else {
    mensagem('erro', 'O elemento já não existia.');
}
header('Location: elementos.php');
exit;