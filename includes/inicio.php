
<?php
// ============================================================
// Inicialização da aplicação
// ============================================================

// BLOCO 1: impedir o armazenamento de páginas em cache
// Evita que o navegador guarde respostas com dados pessoais.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// BLOCO 2: iniciar a sessão
session_start();

// BLOCO 3: ligar à base de dados
require __DIR__ . '/../config/db.php';

// BLOCO 4: guardar mensagem para mostrar na página seguinte
function mensagem($tipo, $texto)
{
    $_SESSION['msg'] = [
        'tipo' => $tipo,
        'texto' => $texto
    ];
}

// BLOCO 5: gerar token CSRF
// Token partilhado pelos formulários que alteram dados.
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// BLOCO 6: validar token CSRF
function csrf_valido(): bool
{
    return isset($_POST['csrf_token'])
        && is_string($_POST['csrf_token'])
        && hash_equals(csrf_token(), $_POST['csrf_token']);
}
