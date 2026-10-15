
<?php
// ============================================================
// Formulário de Tutores
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

    if (!csrf_valido()) {
        $erros[] = 'Pedido inválido ou formulário expirado. Atualiza a página.';
    }

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

        try {
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

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $erros[] = 'Este email já está associado a outro tutor.';
            } else {
                throw $e;
            }
        }
        if (!$erros) {
            header('Location: tutores.php');
            exit;
        }
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
                <i class="fa-solid fa-user-plus"></i>
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

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
            <h2 class="tutor-form-titulo-seccao">
                <i class="fa-solid fa-user-plus"></i>
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
                    <i class="fa-solid fa-floppy-disk"
           aria-hidden="true"></i> Guardar alterações

                </button>

            </div>

        </form>

    </div>

</section>

<?php
// BLOCO 9: rodapé da página
require 'includes/rodape.php';
?>
