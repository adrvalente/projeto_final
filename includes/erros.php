<?php // Lista de erros da validação no servidor (se houver) ?>
<?php if (!empty($erros)): ?>
    <div class="msg erro">
        Corrige o seguinte:
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li><?= htmlspecialchars($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<ul id="erros-js" class="msg erro"></ul>
