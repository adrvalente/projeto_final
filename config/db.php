<?php

//dados para ligar à base de dados:
$host = 'localhost';
$bd = 'scoutgest';
$user = 'root';
$pass = '';

//criar a ligaçao (pdo para segurança contra sql injection):
$pdo = new PDO("mysql:host=$host;dbname=$bd;charset=utf8mb4", $user, $pass);

// se falhar, avisa:
$pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);