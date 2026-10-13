<?php
// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';

// BLOCO 2: valores de partida (sem id = criar; com id = editar)
$id    = (int) ($_GET['id'] ?? 0);
$erros = [];
$tutor = ['nome' => '', 'telefone' => '', 'email' => ''];

// BLOCO 3: se o formulário foi enviado (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // BLOCO 4: ler o que foi escrito
    $tutor = [
        'nome'     => trim($_POST['nome'] ?? ''),
        'telefone' => trim($_POST['telefone'] ?? ''),
        'email'    => trim($_POST['email'] ?? ''),
    ];

    // BLOCO 5: confirmar se está certo
    if ($tutor['nome'] === '' || mb_strlen($tutor['nome']) > 120) {
        $erros[] = 'O nome é obrigatório e não pode ter mais de 120 caracteres.';
    }
    if ($tutor['telefone'] === '' || mb_strlen($tutor['telefone']) > 20) {
        $erros[] = 'O telefone é obrigatório e não pode ter mais de 20 caracteres.';
    }
    // O email é opcional, mas se vier preenchido tem de ter um formato válido
    if ($tutor['email'] !== '' &&
        (mb_strlen($tutor['email']) > 150 || !filter_var($tutor['email'], FILTER_VALIDATE_EMAIL))) {
        $erros[] = 'O email não é válido.';
    }

    // BLOCO 6: se não há erros, gravar (UPDATE se há id, INSERT se não há)
    if (!$erros) {
        if ($id > 0) {
            $stmt = $pdo->prepare(
                'UPDATE tutores SET nome = ?, telefone = ?, email = ? WHERE id_tutor = ?'
            );
            $stmt->execute([$tutor['nome'], $tutor['telefone'], $tutor['email'] ?: null, $id]);
            mensagem('sucesso', 'Tutor atualizado.');
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO tutores (nome, telefone, email) VALUES (?, ?, ?)'
            );
            $stmt->execute([$tutor['nome'], $tutor['telefone'], $tutor['email'] ?: null]);
            mensagem('sucesso', 'Tutor criado.');
        }
        header('Location: tutores.php');
        exit;
    }

// BLOCO 7: se a página só foi aberta para editar (GET com id), carregar o tutor
} elseif ($id > 0) {
    $stmt = $pdo->prepare('SELECT nome, telefone, email FROM tutores WHERE id_tutor = ?');
    $stmt->execute([$id]);
    $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tutor) {
        mensagem('erro', 'Esse tutor não existe.');
        header('Location: tutores.php');
        exit;
    }
}

// BLOCO 8: preparar o que o formulário precisa
$acao   = $id > 0 ? "tutor_form.php?id=$id" : 'tutor_form.php';
$titulo = $id > 0 ? 'Editar tutor' : 'Novo tutor';

// BLOCO 9: o topo e o formulário
require 'includes/cabecalho.php';
?>
<h1><?= $titulo ?></h1>
<?php require 'includes/erros.php'; ?>

<form method="post" action="<?= $acao ?>" id="form-principal" class="formulario" novalidate>
    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" maxlength="120" required
           value="<?= htmlspecialchars($tutor['nome']) ?>">

    <label for="telefone">Telefone</label>
    <input type="text" id="telefone" name="telefone" maxlength="20" required
           value="<?= htmlspecialchars($tutor['telefone']) ?>">

    <label for="email">Email (opcional)</label>
    <input type="email" id="email" name="email" maxlength="150"
           value="<?= htmlspecialchars($tutor['email'] ?? '') ?>">

    <button type="submit">Guardar</button>
</form>
<?php // BLOCO 10: o fundo da página ?>
<?php require 'includes/rodape.php'; ?>