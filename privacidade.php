<?php
// ============================================================
// Política de Privacidade AGR 676 Cristo-Rei — ScoutGest
// ============================================================
declare(strict_types=1);

// BLOCO 1: Configuração
$nomeAgrupamento = 'Agrupamento 676 Cristo-Rei';
$emailPrivacidade = 'geral.676@escutismo.pt';
$dataAtualizacao = '14/10/2026';

// BLOCO 2: Proteção na apresentação dos dados
function e(string $value): string {
    return htmlspecialchars(
        $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <script src="https://kit.fontawesome.com/7a6b0262fa.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="robots" content="noindex, follow">

    <title>Política de Privacidade | ScoutGest</title>

    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- ============================================================
     CABEÇALHO
     ============================================================ -->

<header class="privacy-header">

    <div class="privacy-header-inner">

        <a class="privacy-brand" href="index.php">
            <img
            src="img/logotipo.png"
            alt="ScoutGest — Agrupamento 676 Cristo-Rei"
            class="privacy-logo">
        </a>

        <a class="privacy-back" href="index.php">
            ← Voltar ao início
        </a>

    </div>

</header>

<!-- ============================================================
     CONTEÚDO PRINCIPAL
     ============================================================ -->

<main class="privacy-wrap">

    <!-- INTRODUÇÃO -->

    <div class="privacy-intro" >

        <h1>
            <i class="fa-solid fa-user-secret"></i>
             Política de Privacidade
        </h1>

        <p class="privacy-updated">
            Última atualização:
            <?= e($dataAtualizacao) ?>
        </p>

        <p>
            O ScoutGest é a plataforma de gestão de
            informação do
            <?= e($nomeAgrupamento) ?>,
            destinada a apoiar a organização dos
            elementos, dos respetivos encarregados
            de educação e dos dirigentes.
        </p>

        <p>
            A proteção dos dados pessoais de todos
            os membros do Agrupamento, especialmente
            das crianças e jovens, constitui uma
            prioridade.
        </p>

        <p>
            O tratamento da informação é realizado
            de acordo com os princípios do
            Regulamento Geral sobre a Proteção
            de Dados (RGPD) e da legislação
            nacional aplicável.
        </p>

    </div>

    <article class="privacy-card">

        <!-- ====================================================
             1. RESPONSÁVEL PELO TRATAMENTO
             ==================================================== -->

        <section>

            <h2>1. Responsável pelo tratamento</h2>

            <p>
                O ScoutGest é utilizado pelo
                <?= e($nomeAgrupamento) ?>,
                integrado no Corpo Nacional de Escutas,
                para apoiar a gestão administrativa
                dos seus elementos e encarregados
                de educação.
            </p>

            <p>
                O tratamento dos dados pessoais
                é realizado no âmbito da estrutura
                organizativa do Corpo Nacional
                de Escutas, de acordo com as
                responsabilidades aplicáveis.
            </p>

            <p>
                Para questões relacionadas com
                a privacidade, proteção de dados
                ou exercício dos direitos dos
                titulares, poderá contactar
                a Chefe do Agrupamento - Joana Castro.
            </p>

            <p>
                <strong>Contacto:</strong>
                <a href="mailto:<?= e($emailPrivacidade) ?>">
                    <?= e($emailPrivacidade) ?>
                </a>
            </p>

        </section>

        <!-- ====================================================
             2. DADOS PESSOAIS
             ==================================================== -->

        <section>

            <h2>2. Dados pessoais tratados</h2>

            <p>
                O ScoutGest trata os dados pessoais
                necessários à organização e ao
                acompanhamento dos elementos
                do Agrupamento.
            </p>

            <p><strong>Dados dos elementos:</strong></p>

            <ul>
                <li>Nome completo;</li>
                <li>Número de censo escutista;</li>
                <li>Data de nascimento;</li>
                <li>Secção a que pertence;</li>
                <li>Observações administrativas.</li>
            </ul>

            <p>
                <strong>
                    Dados dos encarregados de educação:
                </strong>
            </p>

            <ul>
                <li>Nome completo;</li>
                <li>Contacto telefónico;</li>
                <li>Endereço de correio eletrónico;</li>
                <li>
                    Associação aos elementos
                    sob a sua responsabilidade.
                </li>
            </ul>

            <p>
                <strong>
                    Dados dos utilizadores e dirigentes:
                </strong>
            </p>

            <ul>
                <li>Nome completo;</li>
                <li>Endereço de correio eletrónico;</li>
                <li>Perfil de acesso;</li>
                <li>Associação ao encarregado de educação;</li>
                <li>Data de criação da conta;</li>
                <li>Registo de aceitação da política;</li>
                <li>Credenciais de autenticação protegidas.</li>
            </ul>

            <p>
                As palavras-passe são armazenadas
                através de mecanismos de hash,
                não sendo guardadas em texto simples.
            </p>

            <p>
                O campo de observações destina-se
                a informações administrativas
                relevantes para a gestão dos
                elementos, devendo ser evitado
                o registo de dados sensíveis
                desnecessários.
            </p>

        </section>

        <!-- ====================================================
             3. FINALIDADES
             ==================================================== -->

        <section>

            <h2>3. Finalidades do tratamento</h2>

            <p>
                Os dados pessoais são utilizados
                para apoiar a organização e o
                funcionamento do Agrupamento.
            </p>

            <ul>
                <li>
                    Manter atualizados os registos
                    dos elementos;
                </li>

                <li>
                    Organizar os elementos
                    pelas respetivas secções;
                </li>

                <li>
                    Identificar os encarregados
                    de educação associados
                    a cada elemento;
                </li>

                <li>
                    Facilitar a comunicação entre
                    dirigentes e encarregados
                    de educação;
                </li>

                <li>
                    Apoiar a gestão administrativa
                    do Agrupamento;
                </li>

                <li>
                    Gerir contas de utilizador
                    e permissões de acesso;
                </li>

                <li>
                    Assegurar o funcionamento
                    e a segurança da plataforma.
                </li>
            </ul>

            <p>
                O tratamento dos dados é realizado
                de acordo com os fundamentos
                jurídicos aplicáveis às atividades
                associativas, às obrigações legais
                e à gestão administrativa
                do Agrupamento.
            </p>

            <p>
                Quando o tratamento depender
                do consentimento, este poderá
                ser retirado nos termos
                legalmente previstos.
            </p>

        </section>

        <!-- ====================================================
             4. ACESSO À INFORMAÇÃO
             ==================================================== -->

        <section>

            <h2>4. Acesso e confidencialidade</h2>

            <p>
                O acesso aos dados pessoais
                está reservado aos utilizadores
                autorizados do ScoutGest.
            </p>

            <p>
                Os dirigentes podem consultar
                e gerir a informação necessária
                ao exercício das suas funções,
                de acordo com as permissões
                atribuídas.
            </p>

            <p>
                Os encarregados de educação
                podem consultar os dados
                que lhes sejam disponibilizados,
                respeitando os níveis
                de acesso definidos.
            </p>

            <p>
                Os dados pessoais não são
                disponibilizados publicamente
                nem utilizados para publicidade,
                marketing ou fins comerciais.
            </p>

        </section>

        <!-- ====================================================
             5. CONSERVAÇÃO
             ==================================================== -->

        <section>

            <h2>5. Conservação dos dados</h2>

            <p>
                Os dados pessoais são conservados
                durante o período necessário
                ao cumprimento das finalidades
                para as quais foram recolhidos.
            </p>

            <p>
                A conservação da informação
                respeita as obrigações legais,
                administrativas e associativas
                aplicáveis ao Agrupamento.
            </p>

            <p>
                Quando os dados deixarem de
                ser necessários, serão eliminados
                ou arquivados de acordo com
                as regras de conservação aplicáveis.
            </p>

            <p>
                Os titulares podem solicitar
                a atualização, retificação
                ou eliminação dos seus dados,
                nos termos previstos no <a href="https://www.dgeg.gov.pt/pt/a-dgeg/rgpd-regulamento-geral-de-protecao-de-dados/"
                target="_blank"
                rel="noopener noreferrer"
                title="Consultar o Regulamento Geral sobre a Proteção de Dados">
        RGPD
    </a>.
            </p>

        </section>

        <!-- ====================================================
             6. SEGURANÇA
             ==================================================== -->

        <section>

            <h2>6. Segurança da informação</h2>

            <p>
                O ScoutGest dispõe de mecanismos
                de autenticação e controlo
                de acesso destinados a proteger
                a informação pessoal.
            </p>

            <p>
                O acesso às funcionalidades
                da plataforma depende do
                perfil e das permissões
                atribuídas a cada utilizador.
            </p>

            <p>
                As palavras-passe são protegidas
                através de mecanismos de hash.
            </p>

            <p>
                A plataforma é disponibilizada
                através de uma ligação HTTPS,
                destinada a proteger a comunicação
                entre o navegador e o servidor.
            </p>

            <p>
                São adotadas medidas técnicas
                e organizativas adequadas
                para proteger a confidencialidade,
                integridade e disponibilidade
                dos dados pessoais.
            </p>

        </section>

        <!-- ====================================================
             7. DIREITOS DOS TITULARES
             ==================================================== -->

        <section>

            <h2>7. Direitos dos titulares</h2>

            <p>
                Nos termos do RGPD, os titulares
                dos dados pessoais podem exercer,
                quando aplicável, os seguintes direitos:
            </p>

            <ul>
                <li>Direito de acesso aos dados;</li>
                <li>Direito de retificação;</li>
                <li>Direito ao apagamento;</li>
                <li>Direito à limitação do tratamento;</li>
                <li>Direito de oposição;</li>
                <li>Direito à portabilidade;</li>
                <li>
                    Direito de retirar o consentimento,
                    quando aplicável.
                </li>
            </ul>

            <p>
                Os encarregados de educação (tutores)
                podem exercer os direitos
                relativos aos menores que
                legalmente representam,
                respeitando os direitos
                próprios das crianças e jovens.
            </p>

            <p>
                Para exercer estes direitos,
                poderá contactar:
                <a href="mailto:<?= e($emailPrivacidade) ?>">
                    <?= e($emailPrivacidade) ?>
                </a>.
            </p>

        </section>

        <!-- ====================================================
             8. CONTA DE UTILIZADOR
             ==================================================== -->

        <section>

            <h2>8. Gestão da conta de utilizador</h2>

            <p>
                Os utilizadores autenticados
                podem consultar os dados
                associados à sua conta
                através da página
                <strong>A Minha Conta</strong>.
            </p>

            <p>
                A plataforma permite solicitar
                ou efetuar a eliminação da
                conta de utilizador, de acordo
                com as condições aplicáveis.
            </p>

            <p>
                A eliminação da conta não implica
                necessariamente a eliminação
                dos registos administrativos
                dos elementos ou encarregados
                de educação associados.
            </p>

            <p>
                Alguns dados poderão ser
                conservados quando exista
                uma obrigação legal
                que justifique a sua manutenção.
            </p>

        </section>

        <!-- ====================================================
             9. COOKIES E SESSÕES
             ==================================================== -->

        <section>

            <h2>9. Cookies e sessões</h2>

            <p>
                O ScoutGest utiliza cookies
                técnicos de sessão necessários
                à autenticação e ao funcionamento
                da plataforma.
            </p>

            <p>
                Estes cookies permitem manter
                a sessão iniciada e identificar
                o utilizador durante a
                navegação nas áreas autorizadas.
            </p>

            <p>
                Os cookies de sessão não são
                utilizados para publicidade
                ou criação de perfis comerciais.
            </p>

        </section>

        <!-- ====================================================
             10. ALOJAMENTO ONLINE
             ==================================================== -->

        <section>

            <h2>10. Alojamento e tratamento online</h2>

            <p>
                O ScoutGest é uma aplicação web
                disponibilizada através da Internet,
                num serviço de alojamento que
                suporta PHP e bases de dados
                MySQL/MariaDB.
            </p>

            <p>
                Os dados pessoais são armazenados
                na infraestrutura do prestador
                de alojamento contratado
                para disponibilizar a plataforma.
            </p>

            <p>
                O acesso à informação pessoal
                está reservado a utilizadores
                autenticados, de acordo com
                os respetivos perfis
                e permissões.
            </p>

            <p>
                O prestador de alojamento
                poderá tratar dados pessoais
                no âmbito da prestação dos
                serviços técnicos contratados,
                respeitando as obrigações
                legais e contratuais aplicáveis.
            </p>

            <p>
                A utilização de serviços
                externos e eventuais
                transferências internacionais
                de dados ficam sujeitas
                às garantias exigidas
                pela legislação de proteção
                de dados.
            </p>

        </section>

        <!-- ====================================================
             11. RECLAMAÇÕES
             ==================================================== -->

        <section>

            <h2>11. Reclamações</h2>

            <p>
                Para qualquer questão relacionada
                com a proteção dos seus dados
                pessoais, poderá contactar
                a Chefe do Agrupamento através
                do endereço:
                <a href="mailto:<?= e($emailPrivacidade) ?>">
                    <?= e($emailPrivacidade) ?>
                </a>.
            </p>

            <p>
                Os titulares têm igualmente
                o direito de apresentar
                reclamação junto da
                <a href="https://www.cnpd.pt/"
                   target="_blank"
                   rel="noopener noreferrer">
                    Comissão Nacional de
                    Proteção de Dados (CNPD)
                </a>,
                autoridade de controlo
                em Portugal.
            </p>

        </section>

        <!-- ====================================================
             12. ALTERAÇÕES À POLÍTICA
             ==================================================== -->

        <section>

            <h2>12. Alterações à política</h2>

            <p>
                Esta Política de Privacidade
                poderá ser atualizada sempre
                que existam alterações relevantes
                ao funcionamento do ScoutGest,
                às práticas de tratamento
                de dados ou às obrigações legais.
            </p>

            <p>
                A data da última atualização
                encontra-se indicada
                no início desta página.
            </p>

        </section>

    </article>

    <!-- ========================================================
         RODAPÉ
         ======================================================== -->

    <footer class="privacy-footer">

        &copy; <?= date('Y') ?>
        ScoutGest · <?= e($nomeAgrupamento) ?>

    </footer>

</main>

</body>
</html>
