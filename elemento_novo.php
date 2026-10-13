<?php
require 'includes/inicio.php';
require 'includes/auth.php';
require 'includes/elemento_funcoes.php';

$erros = [];
$elemento = ['nome' => '', 'id_tutor' => 0, 'data_nascimento' => '', 'seccao' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $elemento = ler_elemento();
    $erros = validar_elemento($pdo, $elemento);

    if (!$erros) {
        $stmt = $pdo->prepare(
            'INSERT INTO elementos (id_tutor, nome, data_nascimento, seccao)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            $elemento['id_tutor'],
            $elemento['nome'],
            $elemento['data_nascimento'],
            $elemento['seccao'] ?: null,        // vazio → NULL
        ]);
        $novoId = $pdo->lastInsertId();

        mensagem('sucesso', 'Elemento criado.');
        header("Location: elemento_ver.php?id=$novoId");
        exit;
    }
}

$tutores = lista_tutores($pdo);
$acao    = 'elemento_novo.php';
$titulo  = 'Novo elemento';
require 'includes/cabecalho.php';
?>
<h1>Novo elemento</h1>
<?php require 'includes/elemento_form.php'; ?>
<?php require 'includes/rodape.php'; ?>