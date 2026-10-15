
<?php
// ============================================================
// ScoutGest — Consulta e Gestão de Tutores
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
exige_dirigente();

// BLOCO 2: receber os filtros
$pesquisa = trim((string) ($_GET['q'] ?? ''));
$associacao = trim((string) ($_GET['associacao'] ?? ''));

// Validar o filtro
if (!in_array($associacao, ['', 'com', 'sem'], true)) {
    $associacao = '';
}

// BLOCO 3: preparar a consulta SQL
$sql = '
    SELECT
        t.id_tutor,
        t.nome,
        t.telefone,
        t.email,
        COUNT(e.id_elemento) AS total_elementos
    FROM tutores t
    LEFT JOIN elementos e
        ON e.id_tutor = t.id_tutor
';

$condicoes = [];
$parametros = [];

// BLOCO 4: pesquisa por nome, telefone ou email
if ($pesquisa !== '') {
    $condicoes[] = '(
        t.nome LIKE ?
        OR t.telefone LIKE ?
        OR t.email LIKE ?
    )';

    $termo = '%' . $pesquisa . '%';
    $parametros = [$termo, $termo, $termo];
}

// BLOCO 5: aplicar condições
if ($condicoes) {
    $sql .= ' WHERE ' . implode(' AND ', $condicoes);
}

// BLOCO 6: agrupar os tutores
$sql .= '
    GROUP BY
        t.id_tutor,
        t.nome,
        t.telefone,
        t.email
';

// BLOCO 7: filtrar por elementos associados
if ($associacao === 'com') {
    $sql .= ' HAVING COUNT(e.id_elemento) > 0';
}

if ($associacao === 'sem') {
    $sql .= ' HAVING COUNT(e.id_elemento) = 0';
}

$sql .= ' ORDER BY t.nome ASC';

// BLOCO 8: executar a consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);
$tutores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// BLOCO 9: contar resultados
$totalTutores = count($tutores);

// BLOCO 10: carregar o cabeçalho
$titulo = 'Gestão de Tutores';
require 'includes/cabecalho.php';
?>

<section class="cartao detalhe gestao-utilizadores gestao-tutores">

    <!-- CABEÇALHO -->
    <div class="gestao-topo">

        <div>
            <h1>
                <i class="fa-solid fa-people-roof"
                   aria-hidden="true"></i>
                Gestão de Tutores
            </h1>

            <p>
                Consulta e gere os tutores do agrupamento
                e os respetivos elementos associados.
            </p>
        </div>

        <span class="gestao-total">
            <?= $totalTutores ?> tutor(es)
        </span>

    </div>

    <!-- PESQUISA E FILTROS -->
    <form
        method="get"
        action="tutores.php"
        class="gestao-pesquisa"
        role="search"
        id="form-filtros"
    >

        <label for="q">
            Pesquisar tutores
        </label>

        <div class="gestao-pesquisa-linha">

            <input
                type="search"
                id="q"
                name="q"
                value="<?= htmlspecialchars($pesquisa, ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Nome, telefone ou email"
            >

            <button
                type="submit"
                class="gestao-botao"
            >
                <i class="fa-solid fa-magnifying-glass"
                   aria-hidden="true"></i>
                Pesquisar
            </button>

            <?php if ($pesquisa !== '' || $associacao !== ''): ?>

                <a
                    href="tutores.php"
                    class="gestao-botao-secundario"
                >
                    Limpar
                </a>

            <?php endif; ?>

        </div>

        <!-- FILTRO -->
        <div class="gestao-filtros">

            <div class="gestao-filtro">

                <label for="associacao">
                    Elementos associados
                </label>

                <select
                    id="associacao"
                    name="associacao"
                    class="gestao-select"
                >
                    <option value="">
                        Todos os tutores
                    </option>

                    <option
                        value="com"
                        <?= $associacao === 'com' ? 'selected' : '' ?>
                    >
                        Com elementos associados
                    </option>

                    <option
                        value="sem"
                        <?= $associacao === 'sem' ? 'selected' : '' ?>
                    >
                        Sem elementos associados
                    </option>

                </select>

            </div>

            <!-- BOTÃO NOVO TUTOR -->
    <div class="gestao-tutores-acoes">

        <a
            href="tutor_form.php"
            class="gestao-botao"
        >
            <i class="fa-solid fa-user-plus"
               aria-hidden="true"></i>
            Novo tutor
        </a>

    </div>

        </div>

    </form>

       <!-- LISTAGEM -->
    <?php if (!$tutores): ?>

        <p class="gestao-tutores-vazio">
            <?php if ($pesquisa !== '' || $associacao !== ''): ?>
                Não foram encontrados tutores
                para os filtros selecionados.
            <?php else: ?>
                Ainda não existem tutores registados.
            <?php endif; ?>
        </p>

    <?php else: ?>

        <div class="gestao-lista">

            <?php foreach ($tutores as $tutor): ?>

                <article class="gestao-item gestao-tutor-item">

                    <div class="gestao-item-dados">

                        <h2>
                            <?= htmlspecialchars($tutor['nome'], ENT_QUOTES, 'UTF-8') ?>
                        </h2>

                        <p>
                            <i class="fa-solid fa-phone"
                               aria-hidden="true"></i>

                            <?= htmlspecialchars($tutor['telefone'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <p>
                            <i class="fa-solid fa-envelope"
                               aria-hidden="true"></i>

                            <?= htmlspecialchars($tutor['email'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="gestao-etiquetas">

                            <?php if ((int) $tutor['total_elementos'] > 0): ?>

                                <span class="gestao-etiqueta associado">
                                    <i class="fa-solid fa-users"
                                       aria-hidden="true"></i>

                                    <?= (int) $tutor['total_elementos'] ?>
                                    elemento(s)
                                </span>

                            <?php else: ?>

                                <span class="gestao-etiqueta pendente">
                                    Sem elementos associados
                                </span>

                            <?php endif; ?>

                            <span class="gestao-etiqueta">
                                Tutor #<?= (int) $tutor['id_tutor'] ?>
                            </span>

                        </div>

                    </div>

                    <!-- AÇÕES -->
                    <div class="gestao-tutor-botoes">

                        <a
                            href="tutor_form.php?id=<?= (int) $tutor['id_tutor'] ?>"
                            class="gestao-botao"
                        >
                            <i class="fa-solid fa-pen"
                               aria-hidden="true"></i>
                            Editar
                        </a>

                        <form
                            method="post"
                            action="tutor_apagar.php"
                            onsubmit="return confirm('Apagar este tutor?');"
                        >

                            <input
                                type="hidden"
                                name="id_tutor"
                                value="<?= (int) $tutor['id_tutor'] ?>"
                            >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>"
                            >

                            <button
                                type="submit"
                                class="gestao-tutor-apagar"
                            >
                                <i class="fa-solid fa-trash"
                                   aria-hidden="true"></i>
                                Apagar
                            </button>

                        </form>

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
    const filtroAssociacao = document.getElementById('associacao');

    if (formulario && filtroAssociacao) {
        filtroAssociacao.addEventListener('change', function () {
            formulario.requestSubmit();
        });
    }

});
</script>

<?php require 'includes/rodape.php'; ?>
