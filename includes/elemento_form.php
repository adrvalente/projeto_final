<?php
// ============================================================
// Formulário de Elementos
// Partilhado por elemento_novo.php e elemento_editar.php
// ============================================================

require 'includes/erros.php';
?>

<form method="post"
      action="<?= htmlspecialchars($acao, ENT_QUOTES, 'UTF-8') ?>"
      id="form-principal"
      class="formulario formulario-elemento"
      novalidate>

    <!-- Proteção CSRF -->
    <input type="hidden"
           name="csrf_token"
           value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

    <!-- Nome -->
    <div class="campo-formulario">
        <label for="nome">Nome *</label>
        <input type="text"
               id="nome"
               name="nome"
               maxlength="120"
               required
               value="<?= htmlspecialchars($elemento['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <!-- Tutor -->
    <div class="campo-formulario">
        <label for="id_tutor">Tutor *</label>

        <select id="id_tutor"
                name="id_tutor"
                required>

            <option value="">— Escolhe um tutor —</option>

            <?php foreach ($tutores as $tutor): ?>
                <option
                    value="<?= (int) $tutor['id_tutor'] ?>"
                    <?= $tutor['id_tutor'] == ($elemento['id_tutor'] ?? 0) ? 'selected' : '' ?>>

                    <?= htmlspecialchars($tutor['nome'], ENT_QUOTES, 'UTF-8') ?>

                </option>
            <?php endforeach; ?>

        </select>
    </div>

    <!-- Secção e Número de Censo -->
    <div class="formulario-linha">

        <div class="campo-formulario">
            <label for="seccao">Secção *</label>

            <input type="text"
                   id="seccao"
                   name="seccao"
                   maxlength="30"
                   required
                   value="<?= htmlspecialchars($elemento['seccao'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="campo-formulario">
            <label for="numero_censo">Número de censo (opcional)</label>

            <input type="text"
                   id="numero_censo"
                   name="numero_censo"
                   maxlength="20"
                   value="<?= htmlspecialchars($elemento['numero_censo'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

    </div>

    <!-- Data de Nascimento -->
    <div class="campo-formulario">
        <label for="data_nascimento">
            Data de nascimento (opcional)
        </label>

        <input type="date"
               id="data_nascimento"
               name="data_nascimento"
               value="<?= htmlspecialchars($elemento['data_nascimento'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <!-- Observações -->
    <div class="campo-formulario">
        <label for="observacoes">
            Observações (opcional)
        </label>

        <textarea id="observacoes"
                  name="observacoes"
                  rows="4"><?= htmlspecialchars($elemento['observacoes'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <!-- Botões -->
    <div class="formulario-acoes">

        <a href="elementos.php"
           class="botao-secundario">
            Cancelar
        </a>

        <button type="submit" class="botao-guardar">
            <i class="fa-solid fa-floppy-disk"
           aria-hidden="true"></i>
         Guardar alterações
        </button>

    </div>

</form>
