
<?php
// ============================================================
// ScoutGest — Gestão de Elementos
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
exige_dirigente();

// BLOCO 2: receber os filtros
$pesquisa = trim((string) ($_GET['q'] ?? ''));
$seccao = trim((string) ($_GET['seccao'] ?? ''));
$idTutor = filter_var(
    $_GET['id_tutor'] ?? '',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
) ?: 0;

// BLOCO 3: carregar as secções existentes
$stmt = $pdo->query(
    "SELECT DISTINCT seccao
     FROM elementos
     WHERE seccao IS NOT NULL AND seccao <> ''
     ORDER BY seccao ASC"
);

$seccoes = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Validar a secção selecionada
if ($seccao !== '' && !in_array($seccao, $seccoes, true)) {
    $seccao = '';
}

// BLOCO 4: carregar os tutores
$stmt = $pdo->query(
    'SELECT id_tutor, nome
     FROM tutores
     ORDER BY nome ASC'
);

$tutores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// BLOCO 5: preparar a consulta SQL
$sql = '
    SELECT
        e.id_elemento,
        e.nome,
        e.numero_censo,
        e.seccao,
        e.id_tutor,
        t.nome AS tutor
    FROM elementos e
    JOIN tutores t
        ON e.id_tutor = t.id_tutor
';

$condicoes = [];
$parametros = [];

// BLOCO 6: pesquisa por nome ou tutor
if ($pesquisa !== '') {

    $condicoes[] = '(
        e.nome LIKE ?
        OR t.nome LIKE ?
    )';

    $termo = '%' . $pesquisa . '%';

    $parametros[] = $termo;
    $parametros[] = $termo;
}

// BLOCO 7: filtrar por secção
if ($seccao !== '') {
    $condicoes[] = 'e.seccao = ?';
    $parametros[] = $seccao;
}

// BLOCO 8: filtrar por tutor
if ($idTutor > 0) {
    $condicoes[] = 'e.id_tutor = ?';
    $parametros[] = $idTutor;
}

// BLOCO 9: juntar as condições
if ($condicoes) {
    $sql .= ' WHERE ' . implode(' AND ', $condicoes);
}

$sql .= ' ORDER BY e.nome ASC';

// BLOCO 10: executar a consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);

$elementos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// BLOCO 11: contar resultados
$totalElementos = count($elementos);

// BLOCO 12: carregar o cabeçalho
$titulo = 'Gestão de Elementos';
require 'includes/cabecalho.php';
?>

<section class="cartao detalhe gestao-utilizadores gestao-elementos">

    <!-- CABEÇALHO -->
    <div class="gestao-topo">

        <div>
            <h1>
                <i class="fa-solid fa-arrows-down-to-people"></i>
                Gestão de Elementos
            </h1>

            <p>
                Consulta os elementos,
                as respetivas secções e os tutores associados.
            </p>
        </div>

        <span class="gestao-total">
            <?= $totalElementos ?> elemento(s)
        </span>

    </div>

    <!-- PESQUISA E FILTROS -->
    <form
        method="get"
        action="elementos.php"
        class="gestao-pesquisa"
        role="search"
        id="form-filtros"
    >

        <!-- PESQUISA -->
        <label for="q">
            Pesquisar elementos
        </label>

        <div class="gestao-pesquisa-linha">

            <input
                type="search"
                id="q"
                name="q"
                value="<?= htmlspecialchars($pesquisa, ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Nome do elemento ou tutor"
            >

            <button
                type="submit"
                class="gestao-botao"
            >
                <i class="fa-solid fa-magnifying-glass"
                   aria-hidden="true"></i>
                Pesquisar
            </button>

            <?php if (
                $pesquisa !== '' ||
                $seccao !== '' ||
                $idTutor > 0
            ): ?>

                <a
                    href="elementos.php"
                    class="gestao-botao-secundario"
                >
                    Limpar
                </a>

            <?php endif; ?>

        </div>

        <!-- FILTROS -->
        <div class="gestao-filtros">

            <!-- FILTRO SECÇÃO -->
            <div class="gestao-filtro">

                <label for="seccao">
                    Secção
                </label>

                <select
                    id="seccao"
                    name="seccao"
                    class="gestao-select"
                >

                    <option value="">
                        Todas as secções
                    </option>

                    <?php foreach ($seccoes as $s): ?>

                        <option
                            value="<?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $seccao === $s ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- FILTRO TUTOR -->
            <div class="gestao-filtro">

                <label for="id_tutor">
                    Tutor associado
                </label>

                <select
                    id="id_tutor"
                    name="id_tutor"
                    class="gestao-select"
                >

                    <option value="">
                        Todos os tutores
                    </option>

                    <?php foreach ($tutores as $tutor): ?>

                        <option
                            value="<?= (int) $tutor['id_tutor'] ?>"
                            <?= $idTutor === (int) $tutor['id_tutor']
                                ? 'selected'
                                : '' ?>
                        >
                            <?= htmlspecialchars(
                                $tutor['nome'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

    </form>

    <!-- NOVO ELEMENTO -->
    <div class="gestao-elementos-acoes">

        <a
            href="elemento_novo.php"
            class="gestao-botao"
        >
            <i class="fa-solid fa-user-plus"
               aria-hidden="true"></i>
            Novo elemento
        </a>

    </div>

    <!-- LISTAGEM -->
    <?php if (!$elementos): ?>

        <p class="gestao-elementos-vazio">

            <?php if (
                $pesquisa !== '' ||
                $seccao !== '' ||
                $idTutor > 0
            ): ?>

                Não foram encontrados elementos
                para os filtros selecionados.

            <?php else: ?>

                Ainda não existem elementos registados.

            <?php endif; ?>

        </p>

    <?php else: ?>

        <div class="gestao-lista">

            <?php foreach ($elementos as $elemento): ?>

                <article class="gestao-item gestao-elemento-item">

                    <div class="gestao-item-dados">

                        <h2>
                            <?= htmlspecialchars(
                                $elemento['nome'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h2>

                        <!-- TUTOR -->
                        <p>
                            <i class="fa-solid fa-user-group"
                               aria-hidden="true"></i>

                            <?= htmlspecialchars(
                                $elemento['tutor'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                        <!-- ETIQUETAS -->
                        <div class="gestao-etiquetas">

                            <span class="gestao-etiqueta associado">
                                <i class="fa-solid fa-campground"
                                   aria-hidden="true"></i>

                                <?= htmlspecialchars(
                                    $elemento['seccao'] ?: 'Sem secção',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                            <?php if (
                                $elemento['numero_censo'] !== null &&
                                $elemento['numero_censo'] !== ''
                            ): ?>

                                <span class="gestao-etiqueta">
                                    <i class="fa-solid fa-id-card"
                                       aria-hidden="true"></i>

                                    
                                    <?= htmlspecialchars(
                                        $elemento['numero_censo'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>

                    <!-- VER DETALHE -->
                    <div class="gestao-elemento-botoes">

                        <a
                            href="elemento_ver.php?id=<?= (int) $elemento['id_elemento'] ?>"
                            class="gestao-botao"
                        >
                            <i class="fa-solid fa-eye"
                               aria-hidden="true"></i>
                            Ver detalhe
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>

<!-- FILTROS AUTOMÁTICOS -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.getElementById('form-filtros');
    const filtroSeccao = document.getElementById('seccao');
    const filtroTutor = document.getElementById('id_tutor');

    if (!formulario) {
        return;
    }

    function aplicarFiltros() {
        formulario.requestSubmit();
    }

    if (filtroSeccao) {
        filtroSeccao.addEventListener('change', aplicarFiltros);
    }

    if (filtroTutor) {
        filtroTutor.addEventListener('change', aplicarFiltros);
    }

});
</script>

<?php require 'includes/rodape.php'; ?>
