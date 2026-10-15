
<?php
// ============================================================
// ScoutGest — Inicialização da Aplicação
// Ficheiro: includes/inicio.php
// ============================================================

// BLOCO 1: impedir o armazenamento de páginas em cache
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// BLOCO 2: configuração da sessão
// Tempo máximo de inatividade: 15 minutos
$sessionLifetime = 900;

ini_set('session.gc_maxlifetime', (string) $sessionLifetime);
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS'])
        && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax'
]);

// BLOCO 3: iniciar a sessão
session_start();

// ============================================================
// Caminho público da aplicação
// ============================================================

// Pasta principal do ScoutGest
$raizProjeto = realpath(__DIR__ . '/..');

// Raiz pública configurada no servidor
$raizPublica = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

// Calcular o caminho relativo à raiz pública
if (
    $raizProjeto !== false &&
    $raizPublica !== false &&
    (
        $raizProjeto === $raizPublica ||
        str_starts_with(
            $raizProjeto,
            $raizPublica . DIRECTORY_SEPARATOR
        )
    )
) {
    $caminhoRelativo = substr(
        $raizProjeto,
        strlen($raizPublica)
    );

    $baseUrl = str_replace(
        DIRECTORY_SEPARATOR,
        '/',
        $caminhoRelativo
    );
} else {
    throw new RuntimeException(
        'Não foi possível determinar o caminho público do ScoutGest.'
    );
}

// Garantir uma barra final
$baseUrl = rtrim($baseUrl, '/') . '/';

// BLOCO 4: verificar timeout por inatividade
if (isset($_SESSION['id_utilizador'])) {

    $ultimaAtividade = $_SESSION['ultima_atividade'] ?? time();

    if ((time() - $ultimaAtividade) >= $sessionLifetime) {

        // Limpar dados da sessão
        $_SESSION = [];

        // Eliminar cookie de sessão
        if (ini_get('session.use_cookies')) {

            $params = session_get_cookie_params();

            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite']
            ]);
        }

        // Destruir sessão
        session_destroy();

        // Redirecionar para o login
        header('Location: ' . $baseUrl . 'login.php?expirada=1');
exit;
        exit;
    }

    // Atualizar última atividade
    $_SESSION['ultima_atividade'] = time();
}

// BLOCO 5: ligar à base de dados
require __DIR__ . '/../config/db.php';

// BLOCO 6: guardar mensagem
function mensagem($tipo, $texto)
{
    $_SESSION['msg'] = [
        'tipo' => $tipo,
        'texto' => $texto
    ];
}

// BLOCO 7: gerar token CSRF
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// BLOCO 8: validar token CSRF
function csrf_valido(): bool
{
    return isset($_POST['csrf_token'])
        && is_string($_POST['csrf_token'])
        && hash_equals(csrf_token(), $_POST['csrf_token']);
}
