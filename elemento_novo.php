<?php
// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
require 'includes/elemento_funcoes.php';

// BLOCO 2: valores de partida (lista de erros vazia e formulário em branco)
$erros = [];
$elemento = [
    'nome' => '', 'id_tutor' => 0, 'numero_censo' => '',
    'data_nascimento' => '', 'seccao' => '', 'observacoes' => '',
];

// BLOCO 3: só corre se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // BLOCO 4: ler o que foi escrito e confirmar se está certo
    $elemento = ler_elemento();
    $erros = validar_elemento($pdo, $elemento);

    // BLOCO 5: se não há erros, gravar
    if (!$erros) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO elementos (id_tutor, nome, numero_censo, data_nascimento, seccao, observacoes)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $elemento['id_tutor'],
                $elemento['nome'],
                $elemento['numero_censo'] ?: null,
                $elemento['data_nascimento'] ?: null,
                $elemento['seccao'],
                $elemento['observacoes'] ?: null,
            ]);
            $novoId = $pdo->lastInsertId();

            mensagem('sucesso', 'Elemento criado.');
            header("Location: elemento_ver.php?id=$novoId");
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
}

// BLOCO 7: preparar o que o formulário precisa
$tutores = lista_tutores($pdo);
$acao    = 'elemento_novo.php';
$titulo  = 'Novo elemento';

// BLOCO 8: o topo e o formulário
require 'includes/cabecalho.php';
?>
<h1>Novo elemento</h1>
<?php require 'includes/elemento_form.php'; ?>
<?php // BLOCO 9: o fundo da página ?>
<?php require 'includes/rodape.php'; ?>