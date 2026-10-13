<?php // Formulário partilhado por elemento_novo.php e elemento_editar.php ?>
<?php require 'includes/erros.php'; ?>

<form method="post" action="<?= $acao ?>" id="form-principal" class="formulario" novalidate>
    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" maxlength="100" required
           value="<?= htmlspecialchars($elemento['nome']) ?>">

    <label for="id_tutor">Tutor</label>
    <select id="id_tutor" name="id_tutor" required>
        <option value="">— escolhe —</option>
        <?php foreach ($tutores as $tutor): ?>
            <?php $sel = $tutor['id_tutor'] == $elemento['id_tutor'] ? 'selected' : ''; ?>
            <option value="<?= $tutor['id_tutor'] ?>" <?= $sel ?>><?= htmlspecialchars($tutor['nome']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="data_nascimento">Data de nascimento</label>
    <input type="date" id="data_nascimento" name="data_nascimento" required
           value="<?= htmlspecialchars($elemento['data_nascimento']) ?>">

    <label for="seccao">Secção (opcional)</label>
    <input type="text" id="seccao" name="seccao" maxlength="30"
           value="<?= htmlspecialchars($elemento['seccao'] ?? '') ?>">

    <button type="submit">Guardar</button>
</form>