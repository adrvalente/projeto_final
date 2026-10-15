
<?php
// ============================================================
// Rodapé do site
// ============================================================
?>

<!-- Fim do conteúdo principal -->
</main>

<!-- ============================================================
     RODAPÉ
     ============================================================ -->

<footer class="rodape">
    <p>
        ScoutGest · Gestão de elementos e tutores ·
        <a href="privacidade.php">
            Política de privacidade
        </a>
    </p>
</footer>

<!-- JavaScript de validação -->
<script src="js/validacao.js"></script>

<!-- JavaScript de animação das mensagens -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Selecionar todas as mensagens de notificação
    const mensagens = document.querySelectorAll(
        '.mensagem, .alerta, .sucesso, .erro, .aviso'
    );

    mensagens.forEach(function (mensagem) {

        // Esperar 5 segundos
        setTimeout(function () {

            // Iniciar animação de desaparecimento
            mensagem.classList.add('mensagem-desaparecer');

            // Remover a mensagem após a animação
            setTimeout(function () {
                mensagem.remove();
            }, 500);

        }, 5000);

    });

});
</script>

</body>
</html>
