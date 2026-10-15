
<?php
// ============================================================
// Criar Elemento
// ============================================================

// BLOCO 1: preparar a página
require 'includes/inicio.php';
require 'includes/auth.php';
require 'includes/elemento_funcoes.php';
exige_dirigente();

// BLOCO 2: valores de partida
$erros = [];
$elemento = [
    'nome' => '',
    'id_tutor' => 0,
    'numero_censo' => '',
    'data_nascimento' => '',
    'seccao' => '',
    'observacoes' => '',
];

// BLOCO 3: só corre se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Impedir pedidos enviados por páginas externas.
    if (!csrf_valido()) {
        http_response_code(403);
        exit('Pedido inválido. Atualiza a página e tenta novamente.');
    }

    // BLOCO 4: ler e validar os dados
    $elemento = ler_elemento();
    $erros = validar_elemento($pdo, $elemento);

    // BLOCO 5: guardar
    if (!$erros) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO elementos (id_tutor, nome, numero_censo, data_nascimento, seccao, observacoes)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );

            $stmt->execute([
                $elemento['id_tutor'],
                $elemento['nome'],
                $elemento['numero_censo'] ?: null,
                $elemento['data_nascimento'] ?: null,
                $elemento['seccao'],
                $elemento['observacoes'] ?: null,
            ]);

            $novoId = $pdo->lastInsertId();

            mensagem('sucesso', 'Elemento criado.');
            header("Location: elemento_ver.php?id=$novoId");
            exit;

        // BLOCO 6: tratar erros da base de dados
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $erros[] = 'Já existe um elemento com esse número de censo.';
            } else {
                throw $e;
            }
        }
    }
}

// BLOCO 7: preparar o formulário
$tutores = lista_tutores($pdo);
$acao = 'elemento_novo.php';
$titulo = 'Novo elemento';

// BLOCO 8: cabeçalho
require 'includes/cabecalho.php';
?>

<section class="tutor-form-pagina">

    <div class="tutor-form-cartao">

        <!-- CABEÇALHO DO CARTÃO -->
        <div class="tutor-form-cabecalho">

            <div class="tutor-form-icone">
                <i class="fa-solid fa-user-plus"></i>
            </div>

            <div class="tutor-form-cabecalho-texto">
                <h1>Novo elemento</h1>
                <p>
                    Preenche os dados para registar um novo
                    elemento no agrupamento.
                </p>
            </div>

        </div>

        <div class="tutor-form-separador"></div>

        <!-- ERROS DE VALIDAÇÃO -->
        <?php require 'includes/erros.php'; ?>

        <!-- FORMULÁRIO -->
        <form method="post"
              action="<?= htmlspecialchars($acao, ENT_QUOTES, 'UTF-8') ?>"
              id="form-principal"
              class="tutor-form-formulario"
              novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

            <h2 class="tutor-form-titulo-seccao">
                <i class="fa-solid fa-user-plus"></i>
                Dados do elemento
            </h2>

            <div class="tutor-form-grelha">

                <!-- NOME -->
                <div class="tutor-form-campo">
                    <label for="nome">Nome</label>
                    <input type="text"
                           id="nome"
                           name="nome"
                           maxlength="120"
                           required
                           value="<?= htmlspecialchars($elemento['nome'], ENT_QUOTES, 'UTF-8') ?>"
                           placeholder="Nome completo do elemento">
                </div>

                <!-- NÚMERO DE CENSO -->
                <div class="tutor-form-campo">
                    <label for="numero_censo">Número de censo</label>
                    <input type="text"
                           id="numero_censo"
                           name="numero_censo"
                           value="<?= htmlspecialchars($elemento['numero_censo'], ENT_QUOTES, 'UTF-8') ?>"
                           placeholder="Número de censo">
                </div>

                <!-- DATA DE NASCIMENTO -->
                <div class="tutor-form-campo">
                    <label for="data_nascimento">
                        Data de nascimento
                    </label>
                    <input type="date"
                           id="data_nascimento"
                           name="data_nascimento"
                           value="<?= htmlspecialchars($elemento['data_nascimento'], ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- SECÇÃO -->
                <div class="tutor-form-campo">
                    <label for="seccao">Secção</label>

                    <select id="seccao"
                            name="seccao"
                            required>

                        <option value="">
                            Selecionar secção
                        </option>

                        <?php foreach ([
                            'Lobitos',
                            'Exploradores',
                            'Pioneiros',
                            'Caminheiros',
                            'Dirigentes'
                        ] as $seccao): ?>

                            <option value="<?= htmlspecialchars($seccao, ENT_QUOTES, 'UTF-8') ?>"
                                <?= $elemento['seccao'] === $seccao ? 'selected' : '' ?>>
                                <?= htmlspecialchars($seccao, ENT_QUOTES, 'UTF-8') ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <!-- TUTOR -->
                <div class="tutor-form-campo elemento-form-largura-total">
                    <label for="id_tutor">
                        Tutor responsável
                    </label>

                    <select id="id_tutor"
                            name="id_tutor"
                            required>

                        <option value="">
                            Selecionar tutor
                        </option>

                        <?php foreach ($tutores as $tutor): ?>

                            <option value="<?= (int) $tutor['id_tutor'] ?>"
                                <?= (int) $elemento['id_tutor'] === (int) $tutor['id_tutor'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tutor['nome'], ENT_QUOTES, 'UTF-8') ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <!-- OBSERVAÇÕES -->
                <div class="tutor-form-campo elemento-form-largura-total">
                    <label for="observacoes">
                        Observações
                    </label>

                    <textarea id="observacoes"
                              name="observacoes"
                              rows="4"
                              placeholder="Observações adicionais..."><?= htmlspecialchars($elemento['observacoes'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

            </div>

            <div class="tutor-form-separador"></div>

            <!-- BOTÕES -->
            <div class="tutor-form-acoes">

                <a href="elementos.php"
                   class="tutor-form-cancelar">
                    Cancelar
                </a>

                <button type="submit"
                        class="tutor-form-guardar">
                    Guardar elemento
                </button>

            </div>

        </form>

    </div>

</section>

<?php
// BLOCO 9: rodapé da página

require 'includes/rodape.php';
?>
