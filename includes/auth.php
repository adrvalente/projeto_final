<?php
 // Controlo de acesso: incluído no topo de cada página restrita,
 // logo a seguir a inicio.php. Sem sessão iniciada, não passa daqui.
 if (!isset($_SESSION['id_utilizador'])) {
 	    mensagem('erro', 'Tens de iniciar sessão para ver essa página.');
 	    header('Location: login.php');
 	    exit;
 }
