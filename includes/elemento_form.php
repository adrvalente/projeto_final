<?php // Formulário partilhado por elemento_novo.php e elemento_editar.php ?>
<?php require 'includes/erros.php'; ?>

<form method="post" action="<?= $acao ?>" id="form-principal" class="formulario" novalidate>
    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" maxlength="120" required
           value="<?= htmlspecialchars($elemento['nome']) ?>">

    <label for="id_tutor">Tutor</label>
    <select id="id_tutor" name="id_tutor" required>
        <option value="">— escolhe —</option>
        <?php foreach ($tutores as $tutor): ?>
            <?php $sel = $tutor['id_tutor'] == $elemento['id_tutor'] ? 'selected' : ''; ?>
            <option value="<?= $tutor['id_tutor'] ?>" <?= $sel ?>><?= htmlspecialchars($tutor['nome']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="seccao">Secção</label>
    <input type="text" id="seccao" name="seccao" maxlength="30" required
           value="<?= htmlspecialchars($elemento['seccao']) ?>">

    <label for="numero_censo">Número de censo (opcional)</label>
    <input type="text" id="numero_censo" name="numero_censo" maxlength="20"
           value="<?= htmlspecialchars($elemento['numero_censo'] ?? '') ?>">

    <label for="data_nascimento">Data de nascimento (opcional)</label>
    <input type="date" id="data_nascimento" name="data_nascimento"
           value="<?= htmlspecialchars($elemento['data_nascimento'] ?? '') ?>">

    <label for="observacoes">Observações (opcional)</label>
    <textarea id="observacoes" name="observacoes" rows="4"><?= htmlspecialchars($elemento['observacoes'] ?? '') ?></textarea>

    <button type="submit">Guardar</button>
</form>