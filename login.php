
<?php
// ============================================================
// ScoutGest — Iniciar Sessão
// Ficheiro: login.php
// ============================================================

// BLOCO 1: inicializar aplicação
require 'includes/inicio.php';

// BLOCO 2: verificar se já existe sessão
if (isset($_SESSION['id_utilizador'])) {
    header('Location: index.php');
    exit;
}

// BLOCO 3: valores iniciais
$erros = [];
$email = '';

// BLOCO 4: verificar sessão expirada
if (
    $_SERVER['REQUEST_METHOD'] === 'GET' &&
    ($_GET['expirada'] ?? '') === '1'
) {
    mensagem(
        'erro',
        'A tua sessão expirou após 15 minutos de inatividade. Inicia sessão novamente.'
    );
}

// BLOCO 5: processar autenticação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar token CSRF
    if (!csrf_valido()) {
        $erros[] = 'Pedido inválido. Atualiza a página e tenta novamente.';
    } else {

        // Ler dados do formulário
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        // Validar campos
        if (
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            mb_strlen($email) > 150 ||
            $password === ''
        ) {
            $erros[] = 'Email ou password errados.';
        } else {

            // Procurar utilizador na base de dados
            $stmt = $pdo->prepare(
                'SELECT id_utilizador, nome, password_hash, tipo
                 FROM utilizadores
                 WHERE email = ?
                 LIMIT 1'
            );

            $stmt->execute([$email]);
            $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar password
            if (
                $utilizador &&
                password_verify(
                    $password,
                    $utilizador['password_hash']
                )
            ) {

                // Renovar identificador de sessão
                session_regenerate_id(true);

                // Guardar dados do utilizador
                $_SESSION['id_utilizador'] =
                    (int) $utilizador['id_utilizador'];

                $_SESSION['nome'] = $utilizador['nome'];
                $_SESSION['tipo'] = $utilizador['tipo'];

                // Inicializar controlo de inatividade
                $_SESSION['ultima_atividade'] = time();

                // Renovar token CSRF
                unset($_SESSION['csrf_token']);

                // Mensagem de sucesso
                mensagem(
                    'sucesso',
                    'Sessão iniciada. Bem-vindo, ' .
                    $utilizador['nome'] . '!'
                );

                // Redirecionar
                header('Location: index.php');
                exit;
            }

            // Credenciais inválidas
            $erros[] = 'Email ou password errados.';
        }
    }
}

// BLOCO 6: carregar cabeçalho
$titulo = 'Iniciar sessão';
require 'includes/cabecalho.php';
?>

<!-- ============================================================
     FORMULÁRIO DE AUTENTICAÇÃO
     ============================================================ -->

<section class="login-container">

    <div class="login-card">

        <?php require 'includes/erros.php'; ?>

        <form
            action="login.php"
            method="post"
            class="login-form"
        >

            <!-- Proteção CSRF -->
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(
                    csrf_token(),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

            <!-- Email -->
            <div class="login-campo">

                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars(
                        $email,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    placeholder="exemplo@email.pt"
                    autocomplete="username"
                    maxlength="150"
                    required
                >

            </div>

            <!-- Password -->
            <div class="login-campo">

                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Introduz a tua password"
                    autocomplete="current-password"
                    required
                >

            </div>

            <!-- Botão entrar -->
            <button
                type="submit"
                class="btn login-btn"
            >
                Entrar
            </button>

        </form>

        <!-- Criar conta -->
        <div class="login-footer">

            <p>Ainda não tens conta?</p>

            <a
                href="registo.php"
                class="btn secundario login-btn-criar"
            >
                Criar conta
            </a>

        </div>

    </div>

</section>

<?php require 'includes/rodape.php'; ?>
