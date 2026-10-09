<?php

//ativar a sessao:
session_star();

//ligar a db;
require __DIR__.'/../config/db.php';

//guardar mensagem para mostrar na pagina seguinte
function mensagem($tipo, $texto)
{
    $_SESSION['msg']=['tipo' => $tipo, 'texto' => $texto];
}
