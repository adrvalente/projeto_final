
<?php
// ============================================================
// Gestão de Utilizadores
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
exige_dirigente();

// BLOCO 2: receber os filtros
$pesquisa = trim((string) ($_GET['q'] ?? ''));
$tipo = trim((string) ($_GET['tipo'] ?? ''));
$associacao = trim((string) ($_GET['associacao'] ?? ''));

// Validar os valores dos filtros
if (!in_array($tipo, ['', 'Utilizador', 'Dirigente'], true)) {
    $tipo = '';
}

if (!in_array($associacao, ['', 'associados', 'pendentes'], true)) {
    $associacao = '';
}

// BLOCO 3: preparar a consulta SQL
$sql = '
    SELECT
        u.id_utilizador,
        u.nome,
        u.email,
        u.tipo,
        u.criado_em,
        u.id_tutor,
        t.nome AS nome_tutor,
        (
            SELECT COUNT(*)
            FROM elementos e
            WHERE e.id_tutor = u.id_tutor
        ) AS total_elementos
    FROM utilizadores u
    LEFT JOIN tutores t ON t.id_tutor = u.id_tutor
';

$condicoes = [];
$parametros = [];

// BLOCO 4: aplicar pesquisa
if ($pesquisa !== '') {
    $condicoes[] = '(
        u.nome LIKE ?
        OR u.email LIKE ?
        OR t.nome LIKE ?
    )';

    $termo = '%' . $pesquisa . '%';
    $parametros[] = $termo;
    $parametros[] = $termo;
    $parametros[] = $termo;
}

// BLOCO 5: filtrar por tipo de conta
if ($tipo !== '') {
    $condicoes[] = 'u.tipo = ?';
    $parametros[] = $tipo;
}

// BLOCO 6: filtrar por associação ao tutor
if ($associacao === 'associados') {
    $condicoes[] = 'u.id_tutor IS NOT NULL';
}

if ($associacao === 'pendentes') {
    $condicoes[] = 'u.id_tutor IS NULL';
}

// BLOCO 7: juntar as condições
if ($condicoes) {
    $sql .= ' WHERE ' . implode(' AND ', $condicoes);
}

$sql .= ' ORDER BY u.nome ASC';

// BLOCO 8: executar a consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);
$utilizadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// BLOCO 9: contagem de resultados
$totalUtilizadores = count($utilizadores);

// BLOCO 10: carregar o cabeçalho
$titulo = 'Gestão de Utilizadores';
require 'includes/cabecalho.php';
?>

<section class="cartao detalhe gestao-utilizadores">

    <!-- CABEÇALHO -->
    <div class="gestao-topo">
        <div>
            <h1>
                <i class="fa-solid fa-users-gear"
                   aria-hidden="true"></i>
                Gestão de Utilizadores
            </h1>

            <p>
                Consulta as contas registadas e associa-as
                aos tutores do agrupamento.
            </p>
        </div>

        <span class="gestao-total">
            <?= $totalUtilizadores ?> conta(s)
        </span>
    </div>

    <!-- PESQUISA E FILTROS -->
    <form
        method="get"
        action="utilizadores.php"
        class="gestao-pesquisa"
        role="search"
        id="form-filtros"
    >

        <!-- PESQUISA -->
        <label for="q">
            Pesquisar utilizadores
        </label>

        <div class="gestao-pesquisa-linha">

            <input
                type="search"
                id="q"
                name="q"
                value="<?= htmlspecialchars($pesquisa, ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Nome, email ou tutor associado"
            >

            <button
                type="submit"
                class="gestao-botao"
            >
                Pesquisar
            </button>

            <?php if (
                $pesquisa !== '' ||
                $tipo !== '' ||
                $associacao !== ''
            ): ?>

                <a
                    href="utilizadores.php"
                    class="gestao-botao-secundario"
                >
                    Limpar
                </a>

            <?php endif; ?>

        </div>

        <!-- FILTROS -->
        <div class="gestao-filtros">

            <div class="gestao-filtro">

                <label for="tipo">
                    Tipo de conta
                </label>

                <select
                    id="tipo"
                    name="tipo"
                    class="gestao-select"
                >
                    <option value="">
                        Todos
                    </option>

                    <option
                        value="Utilizador"
                        <?= $tipo === 'Utilizador' ? 'selected' : '' ?>
                    >
                        Utilizadores
                    </option>

                    <option
                        value="Dirigente"
                        <?= $tipo === 'Dirigente' ? 'selected' : '' ?>
                    >
                        Dirigentes
                    </option>
                </select>

            </div>

            <div class="gestao-filtro">

                <label for="associacao">
                    Associação ao tutor
                </label>

                <select
                    id="associacao"
                    name="associacao"
                    class="gestao-select"
                >
                    <option value="">
                        Todos
                    </option>

                    <option
                        value="associados"
                        <?= $associacao === 'associados' ? 'selected' : '' ?>
                    >
                        Com tutor associado
                    </option>

                    <option
                        value="pendentes"
                        <?= $associacao === 'pendentes' ? 'selected' : '' ?>
                    >
                        Sem tutor associado
                    </option>
                </select>

            </div>

        </div>

    </form>

    <!-- LISTAGEM DE UTILIZADORES -->
    <?php if (!$utilizadores): ?>

        <p>
            Não foram encontradas contas
            para os filtros selecionados.
        </p>

    <?php else: ?>

        <div class="gestao-lista">

            <?php foreach ($utilizadores as $u): ?>

                <article class="gestao-item">

                    <div class="gestao-item-dados">

                        <h2>
                            <?= htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8') ?>
                        </h2>

                        <p>
                            <?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="gestao-etiquetas">

                            <!-- TIPO DE CONTA -->
                            <span class="gestao-etiqueta">
                                <?= htmlspecialchars($u['tipo'], ENT_QUOTES, 'UTF-8') ?>
                            </span>

                            <!-- ASSOCIAÇÃO AO TUTOR -->
                            <?php if ($u['id_tutor'] !== null): ?>

                                <span class="gestao-etiqueta associado">
                                    Tutor #<?= (int) $u['id_tutor'] ?>
                                    —
                                    <?= htmlspecialchars($u['nome_tutor'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    (<?= (int) $u['total_elementos'] ?> elementos)
                                </span>

                            <?php else: ?>

                                <span class="gestao-etiqueta pendente">
                                    Sem tutor associado
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>

                    <!-- GERIR CONTA -->
                    <a
                        class="gestao-botao"
                        href="utilizador_editar.php?id=<?= (int) $u['id_utilizador'] ?>"
                    >
                        Gerir conta
                    </a>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>

<!-- FILTROS AUTOMÁTICOS -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.getElementById('form-filtros');
    const filtroTipo = document.getElementById('tipo');
    const filtroAssociacao = document.getElementById('associacao');

    function aplicarFiltros() {
        formulario.requestSubmit();
    }

    filtroTipo.addEventListener('change', aplicarFiltros);
    filtroAssociacao.addEventListener('change', aplicarFiltros);

});
</script>

<?php require 'includes/rodape.php'; ?>
