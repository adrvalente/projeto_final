<?php
require 'includes/inicio.php';

// Apaga os dados da sessão e destrói-a
$_SESSION = [];
session_destroy();

// Nova sessão, só para levar a mensagem até à página seguinte
session_start();
mensagem('sucesso', 'Sessão terminada.');
header('Location: index.php');
exit;