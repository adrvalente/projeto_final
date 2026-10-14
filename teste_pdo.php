<?php
require 'config/db.php';

// BLOCO 1: o texto da pesquisa vem do endereço (?q=...)
$q = $_GET['q'] ?? '';

// BLOCO 2: preparar e executar (o valor vai à parte)
$stmt = $pdo->prepare('SELECT id_elemento, nome FROM elementos WHERE nome LIKE ?');
$stmt->execute(['%' . $q . '%']);

// BLOCO 3: ler todas as linhas
$elementos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// BLOCO 4: mostrar
foreach ($elementos as $e) {
    echo $e['id_elemento'] . ' - ' . $e['nome'] . '<br>';
}