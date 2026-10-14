
<?php
// ============================================================
// ScoutGest — Ficha de Detalhe do Elemento
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';

// BLOCO 2: ver qual é o elemento pedido
$id = (int) ($_GET['id'] ?? 0);

// BLOCO 3: pedir esse elemento à base de dados
$stmt = $pdo->prepare(
    'SELECT e.*, t.nome AS tutor, t.telefone, t.email
     FROM elementos e
     JOIN tutores t ON e.id_tutor = t.id_tutor
     WHERE e.id_elemento = ?'
);

$stmt->execute([$id]);
$elemento = $stmt->fetch(PDO::FETCH_ASSOC);

// BLOCO 4: e se o elemento não existir?
if (!$elemento) {
    http_response_code(404);

    $titulo = 'Elemento não encontrado';
    require 'includes/cabecalho.php';
    ?>

    <h1>Elemento não encontrado</h1>

    <p>
        <a href="elementos.php" class="btn secundario">
            Voltar à lista
        </a>
    </p>

    <?php
    require 'includes/rodape.php';
    exit;
}

// BLOCO 5: o topo da página
$titulo = $elemento['nome'];
require 'includes/cabecalho.php';
?>

<h1>
    <?= htmlspecialchars($elemento['nome'], ENT_QUOTES, 'UTF-8') ?>
</h1>

<!-- BLOCO 6: cartão de detalhe -->
<article class="cartao elemento-detalhe">

    <!-- Duas colunas -->
    <div class="elemento-detalhe-grid">

        <!-- Coluna esquerda: dados do elemento -->
        <section class="elemento-dados">

            <h2>Dados do elemento</h2>

            <dl>
                <dt>Secção</dt>
                <dd>
                    <?= htmlspecialchars($elemento['seccao'], ENT_QUOTES, 'UTF-8') ?>
                </dd>

                <dt>Número de censo</dt>
                <dd>
                    <?= htmlspecialchars($elemento['numero_censo'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
                </dd>

                <dt>Data de nascimento</dt>
                <dd>
                    <?= $elemento['data_nascimento']
                        ? date('d/m/Y', strtotime($elemento['data_nascimento']))
                        : '—' ?>
                </dd>
            </dl>

        </section>

        <!-- Coluna direita: dados do tutor -->
        <section class="elemento-tutor">

            <h2>Dados do tutor</h2>

            <dl>
                <dt>Tutor</dt>
                <dd>
                    <?= htmlspecialchars($elemento['tutor'], ENT_QUOTES, 'UTF-8') ?>
                </dd>

                <dt>Telefone do tutor</dt>
                <dd>
                    <?= htmlspecialchars($elemento['telefone'], ENT_QUOTES, 'UTF-8') ?>
                </dd>

                <dt>Email do tutor</dt>
                <dd>
                    <?= htmlspecialchars($elemento['email'] ?: '—', ENT_QUOTES, 'UTF-8') ?>
                </dd>
            </dl>

        </section>

    </div>

    <!-- Observações: largura completa -->
    <section class="elemento-observacoes">

        <h2>Observações</h2>

        <p>
            <?= nl2br(htmlspecialchars(
                $elemento['observacoes'] ?: '—',
                ENT_QUOTES,
                'UTF-8'
            )) ?>
        </p>

    </section>

</article>

<!-- BLOCO 7: botões fora do cartão -->
<div class="acoes elemento-detalhe-acoes">

    <a
        href="elemento_editar.php?id=<?= (int) $elemento['id_elemento'] ?>"
        class="btn"
    >
        Editar
    </a>

    <form
        method="post"
        action="elemento_apagar.php"
        onsubmit="return confirm('Apagar este elemento?');"
    >
        <input
            type="hidden"
            name="id_elemento"
            value="<?= (int) $elemento['id_elemento'] ?>"
        >

        <button type="submit" class="btnperigo">
            Apagar
        </button>
    </form>

    <a href="elementos.php" class="btn secundario">
        Voltar
    </a>

</div>

<?php
// BLOCO 8: o fundo da página
require 'includes/rodape.php';
?>
