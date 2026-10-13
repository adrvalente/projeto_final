<?php
// Funções usadas por elemento_novo.php e elemento_editar.php

// Lê os campos do formulário para um array
function ler_elemento()
{
    return [
        'nome'            => trim($_POST['nome'] ?? ''),
        'id_tutor'        => (int) ($_POST['id_tutor'] ?? 0),
        'numero_censo'    => trim($_POST['numero_censo'] ?? ''),
        'data_nascimento' => trim($_POST['data_nascimento'] ?? ''),
        'seccao'          => trim($_POST['seccao'] ?? ''),
        'observacoes'     => trim($_POST['observacoes'] ?? ''),
    ];
}

// Validação no servidor: devolve a lista de erros (vazia = tudo certo)
function validar_elemento($pdo, $elemento)
{
    $erros = [];

    if ($elemento['nome'] === '' || mb_strlen($elemento['nome']) > 120) {
        $erros[] = 'O nome é obrigatório e não pode ter mais de 120 caracteres.';
    }

    // O tutor tem de existir mesmo na base de dados
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM tutores WHERE id_tutor = ?');
    $stmt->execute([$elemento['id_tutor']]);
    if ($stmt->fetchColumn() == 0) {
        $erros[] = 'Escolhe um tutor da lista.';
    }

    if ($elemento['seccao'] === '' || mb_strlen($elemento['seccao']) > 30) {
        $erros[] = 'A secção é obrigatória e não pode ter mais de 30 caracteres.';
    }

    if (mb_strlen($elemento['numero_censo']) > 20) {
        $erros[] = 'O número de censo não pode ter mais de 20 caracteres.';
    }

    // A data é opcional, mas se vier preenchida tem de ser válida e não pode ser no futuro
    $data = $elemento['data_nascimento'];
    if ($data !== '' && (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $data) || $data > date('Y-m-d'))) {
        $erros[] = 'A data de nascimento não é válida ou está no futuro.';
    }

    return $erros;
}

// Lista de tutores para a caixa de seleção do formulário
function lista_tutores($pdo)
{
    $stmt = $pdo->prepare('SELECT id_tutor, nome FROM tutores ORDER BY nome');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}