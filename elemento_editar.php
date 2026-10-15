
<?php
// ============================================================
// Editar Elemento
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
require 'includes/elemento_funcoes.php';
exige_dirigente();

// BLOCO 2: identificar o elemento
$id = (int) ($_GET['id'] ?? 0);
$erros = [];

// BLOCO 3: processar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar proteção CSRF
    if (!csrf_valido()) {
        http_response_code(403);
        exit('Pedido inválido. Atualiza a página e tenta novamente.');
    }

    // Ler e validar os dados
    $elemento = ler_elemento();
    $erros = validar_elemento($pdo, $elemento);

    // BLOCO 4: guardar alterações
    if (!$erros) {
        try {
            $stmt = $pdo->prepare(
                'UPDATE elementos
                 SET id_tutor = ?,
                     nome = ?,
                     numero_censo = ?,
                     data_nascimento = ?,
                     seccao = ?,
                     observacoes = ?
                 WHERE id_elemento = ?'
            );

            $stmt->execute([
                $elemento['id_tutor'],
                $elemento['nome'],
                $elemento['numero_censo'] ?: null,
                $elemento['data_nascimento'] ?: null,
                $elemento['seccao'],
                $elemento['observacoes'] ?: null,
                $id
            ]);

            mensagem('sucesso', 'Alterações guardadas.');
            header("Location: elemento_ver.php?id=$id");
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $erros[] = 'Já existe um elemento com esse número de censo.';
            } else {
                throw $e;
            }
        }
    }

// BLOCO 5: carregar dados existentes
} else {

    $stmt = $pdo->prepare(
        'SELECT * FROM elementos WHERE id_elemento = ?'
    );
    $stmt->execute([$id]);
    $elemento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$elemento) {
        mensagem('erro', 'Esse elemento não existe.');
        header('Location: elementos.php');
        exit;
    }
}

// BLOCO 6: preparar formulário
$tutores = lista_tutores($pdo);
$acao = "elemento_editar.php?id=$id";
$titulo = 'Editar elemento';

// BLOCO 7: apresentar página
require 'includes/cabecalho.php';
?>

<section class="cartao">

    <h1>Editar elemento</h1>

    <p>
        Atualiza os dados do elemento e guarda
        as alterações efetuadas.
    </p>

    <?php require 'includes/elemento_form.php'; ?>

</section>

<?php
// BLOCO 8: rodapé da página
require 'includes/rodape.php';
?>
