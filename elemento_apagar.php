<?php
require 'includes/inicio.php';
require 'includes/auth.php';

// Só aceita POST: um link (GET) nunca deve apagar dados
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: elementos.php');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM elementos WHERE id_elemento = ?');
$stmt->execute([(int) ($_POST['id_elemento'] ?? 0)]);

// rowCount() diz quantas linhas foram apagadas
if ($stmt->rowCount() === 1) {
    mensagem('sucesso', 'Elemento apagado.');
} else {
    mensagem('erro', 'O elemento já não existia.');
}
header('Location: elementos.php');
exit;