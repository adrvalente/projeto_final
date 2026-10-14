
<?php
// ============================================================
// ScoutGest — Formulário de Tutores
// Ficheiro: tutor_form.php
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
exige_dirigente();

// BLOCO 2: valores iniciais
$id = (int) ($_GET['id'] ?? 0);
$erros = [];

$tutor = [
    'nome' => '',
    'telefone' => '',
    'email' => ''
];

// BLOCO 3: processar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tutor = [
        'nome' => trim($_POST['nome'] ?? ''),
        'telefone' => trim($_POST['telefone'] ?? ''),
        'email' => trim($_POST['email'] ?? '')
    ];

    // BLOCO 4: validação
    if ($tutor['nome'] === '' ||
        mb_strlen($tutor['nome']) > 120) {
        $erros[] = 'O nome é obrigatório e não pode ter mais de 120 caracteres.';
    }

    if ($tutor['telefone'] === '' ||
        mb_strlen($tutor['telefone']) > 20) {
        $erros[] = 'O telefone é obrigatório e não pode ter mais de 20 caracteres.';
    }

    if ($tutor['email'] !== '' &&
        (mb_strlen($tutor['email']) > 150 ||
        !filter_var($tutor['email'], FILTER_VALIDATE_EMAIL))) {
        $erros[] = 'O email não é válido.';
    }

    // BLOCO 5: guardar na base de dados
    if (!$erros) {

        if ($id > 0) {

            $stmt = $pdo->prepare(
                'UPDATE tutores
                 SET nome = ?, telefone = ?, email = ?
                 WHERE id_tutor = ?'
            );

            $stmt->execute([
                $tutor['nome'],
                $tutor['telefone'],
                $tutor['email'] ?: null,
                $id
            ]);

            mensagem('sucesso', 'Tutor atualizado.');

        } else {

            $stmt = $pdo->prepare(
                'INSERT INTO tutores
                 (nome, telefone, email)
                 VALUES (?, ?, ?)'
            );

            $stmt->execute([
                $tutor['nome'],
                $tutor['telefone'],
                $tutor['email'] ?: null
            ]);

            mensagem('sucesso', 'Tutor criado.');
        }

        header('Location: tutores.php');
        exit;
    }

// BLOCO 6: carregar tutor para edição
} elseif ($id > 0) {

    $stmt = $pdo->prepare(
        'SELECT id_tutor, nome, telefone, email
         FROM tutores
         WHERE id_tutor = ?'
    );

    $stmt->execute([$id]);
    $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tutor) {
        mensagem('erro', 'Esse tutor não existe.');
        header('Location: tutores.php');
        exit;
    }
}

// BLOCO 7: configurar página
$acao = $id > 0
    ? "tutor_form.php?id=$id"
    : 'tutor_form.php';

$titulo = $id > 0
    ? 'Editar tutor'
    : 'Novo tutor';

$descricao = $id > 0
    ? 'Atualiza os dados do tutor selecionado.'
    : 'Preenche os dados para registar um novo tutor.';

// BLOCO 8: cabeçalho
require 'includes/cabecalho.php';
?>

<section class="tutor-form-pagina">

    <div class="tutor-form-cartao">

        <!-- CABEÇALHO -->
        <div class="tutor-form-cabecalho">

            <div class="tutor-form-icone">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="34" height="34"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M20 8v6"/>
                    <path d="M23 11h-6"/>
                </svg>
            </div>

            <div class="tutor-form-cabecalho-texto">
                <h1><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h1>
                <p><?= htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') ?></p>
            </div>

        </div>

        <div class="tutor-form-separador"></div>

        <!-- ERROS -->
        <?php require 'includes/erros.php'; ?>

        <!-- FORMULÁRIO -->
        <form method="post"
              action="<?= htmlspecialchars($acao, ENT_QUOTES, 'UTF-8') ?>"
              id="form-principal"
              class="tutor-form-formulario"
              novalidate>

            <h2 class="tutor-form-titulo-seccao">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="25" height="25"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M20 8v6"/>
                    <path d="M23 11h-6"/>
                </svg>
                Dados do tutor
            </h2>

            <div class="tutor-form-grelha">

                <!-- NOME -->
                <div class="tutor-form-campo">
                    <label for="nome">Nome</label>
                    <input type="text"
                           id="nome"
                           name="nome"
                           maxlength="120"
                           autocomplete="name"
                           required
                           value="<?= htmlspecialchars($tutor['nome'], ENT_QUOTES, 'UTF-8') ?>"
                           placeholder="Nome completo do tutor">
                </div>

                <!-- TELEFONE -->
                <div class="tutor-form-campo">
                    <label for="telefone">Telefone</label>
                    <input type="tel"
                           id="telefone"
                           name="telefone"
                           maxlength="20"
                           autocomplete="tel"
                           required
                           value="<?= htmlspecialchars($tutor['telefone'], ENT_QUOTES, 'UTF-8') ?>"
                           placeholder="Número de telefone">
                </div>

                <!-- EMAIL -->
                <div class="tutor-form-campo">
                    <label for="email">Email (opcional)</label>
                    <input type="email"
                           id="email"
                           name="email"
                           maxlength="150"
                           autocomplete="email"
                           value="<?= htmlspecialchars($tutor['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           placeholder="exemplo@email.pt">
                </div>

                <!-- ID DO TUTOR -->
                <div class="tutor-form-campo">
                    <label for="id_tutor">ID do Tutor</label>
                    <input type="text"
                           id="id_tutor"
                           value="<?= $id > 0
                               ? htmlspecialchars((string) ($tutor['id_tutor'] ?? $id), ENT_QUOTES, 'UTF-8')
                               : 'Atribuído automaticamente' ?>"
                           readonly
                           aria-readonly="true">
                </div>

            </div>

            <div class="tutor-form-separador"></div>

            <!-- AÇÕES -->
            <div class="tutor-form-acoes">

                <a href="tutores.php"
                   class="tutor-form-cancelar">
                    Cancelar
                </a>

                <button type="submit"
                        class="tutor-form-guardar">
                    <?= $id > 0 ? 'Guardar alterações' : 'Guardar tutor' ?>
                </button>

            </div>

        </form>

    </div>

</section>

<?php
// BLOCO 9: rodapé
require 'includes/rodape.php';
?>
