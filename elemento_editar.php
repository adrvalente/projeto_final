<?php
// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
require 'includes/elemento_funcoes.php';
exige_dirigente();

// BLOCO 2: qual é o elemento a editar e valores de partida
$id    = (int) ($_GET['id'] ?? 0);
$erros = [];

// BLOCO 3: se o formulário foi enviado (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // BLOCO 4: ler o que foi escrito e confirmar se está certo
    $elemento = ler_elemento();
    $erros = validar_elemento($pdo, $elemento);

    // BLOCO 5: se não há erros, gravar as alterações
    if (!$erros) {
        try {
            $stmt = $pdo->prepare(
                'UPDATE elementos
                 SET id_tutor = ?, nome = ?, numero_censo = ?, data_nascimento = ?,
                     seccao = ?, observacoes = ?
                 WHERE id_elemento = ?'
            );
            $stmt->execute([
                $elemento['id_tutor'],
                $elemento['nome'],
                $elemento['numero_censo'] ?: null,
                $elemento['data_nascimento'] ?: null,
                $elemento['seccao'],
                $elemento['observacoes'] ?: null,
                $id,
            ]);

            mensagem('sucesso', 'Alterações guardadas.');
            header("Location: elemento_ver.php?id=$id");
            exit;

        // BLOCO 6: se a base de dados recusar, tratar o erro
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $erros[] = 'Já existe um elemento com esse número de censo.';
            } else {
                throw $e;
            }
        }
    }

// BLOCO 7: se a página só foi aberta (GET), carregar o elemento para preencher o formulário
} else {
    $stmt = $pdo->prepare('SELECT * FROM elementos WHERE id_elemento = ?');
    $stmt->execute([$id]);
    $elemento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$elemento) {
        mensagem('erro', 'Esse elemento não existe.');
        header('Location: elementos.php');
        exit;
    }
}

// BLOCO 8: preparar o que o formulário precisa
$tutores = lista_tutores($pdo);
$acao    = "elemento_editar.php?id=$id";
$titulo  = 'Editar elemento';

// BLOCO 9: o topo e o formulário
require 'includes/cabecalho.php';
?>
<h1>Editar elemento</h1>
<?php require 'includes/elemento_form.php'; ?>
<?php // BLOCO 10: o fundo da página ?>
<?php require 'includes/rodape.php'; ?>