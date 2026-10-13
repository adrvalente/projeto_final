<?php
// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';

// BLOCO 2: só aceita POST (um link nunca deve apagar dados)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tutores.php');
    exit;
}

// BLOCO 3: tentar apagar o tutor
try {
    $stmt = $pdo->prepare('DELETE FROM tutores WHERE id_tutor = ?');
    $stmt->execute([(int) ($_POST['id_tutor'] ?? 0)]);
    mensagem('sucesso', 'Tutor apagado.');

// BLOCO 4: se a base de dados recusar, explicar porquê
} catch (PDOException $e) {
    // 23000 = uma regra foi violada: o tutor ainda tem elementos (ON DELETE RESTRICT)
    if ($e->getCode() == 23000) {
        mensagem('erro', 'Não é possível apagar este tutor, porque tem elementos registados.');
    } else {
        throw $e;
    }
}

// BLOCO 5: voltar à lista
header('Location: tutores.php');
exit;