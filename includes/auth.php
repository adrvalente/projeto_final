<?php
 // Controlo de acesso: incluído no topo de cada página restrita,
 // logo a seguir a inicio.php. Sem sessão iniciada, não passa daqui.
 if (!isset($_SESSION['id_utilizador'])) {
 	    mensagem('erro', 'Tens de iniciar sessão para ver essa página.');
 	    header('Location: login.php');
 	    exit;
 }

 // BLOCO 2: uma função para as páginas só do dirigente (perfil do dossier)
function exige_dirigente()
{
    if (($_SESSION['tipo'] ?? '') !== 'Dirigente') {
        mensagem('erro', 'Não tens permissão para aceder a essa página.');
        header('Location: index.php');
        exit;
    }
}