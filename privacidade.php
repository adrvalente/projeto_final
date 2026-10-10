<?php
/**
 * ScoutGest — Política de Privacidade
 * UC00615 · Stack A (PHP + PDO + MySQL/MariaDB)
 */
declare(strict_types=1);

// Variáveis para a política de privacidade
$nomeAgrupamento = 'Agrupamento 676 Cristo-Rei';
$emailPrivacidade = 'geral.676@escutismo.pt';
$dataAtualizacao = '10/10/2026';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, follow">
    <title>Política de Privacidade | ScoutGest</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="privacy-header">
    <div class="privacy-header-inner">
        <a class="privacy-brand" href="index.php">Scout<span>Gest</span></a>
        <a class="privacy-back" href="index.php">← Voltar ao início</a>
    </div>
</header>
<main class="privacy-wrap">
    <div class="privacy-intro">
        <h1>Política de Privacidade</h1>
        <p class="privacy-updated">Última atualização: <?= e($dataAtualizacao) ?></p>
        <p>O ScoutGest é uma aplicação de apoio à organização e gestão de informação do <?= e($nomeAgrupamento) ?>. A proteção dos dados pessoais, em especial dos menores, é uma prioridade.</p>
    </div>
    <article class="privacy-card">
        <section>
            <h2>1. Responsável pelo tratamento</h2>
            <p>O responsável pelo tratamento dos dados pessoais é o chefe de agrupamento.</p>
            <p><strong>Entidade de referência:</strong> <?= e($nomeAgrupamento) ?>.</p>
            <?php if ($emailPrivacidade !== ''): ?>
                <p><strong>Contacto para privacidade:</strong> <a href="mailto:<?= e($emailPrivacidade) ?>"><?= e($emailPrivacidade) ?></a></p>
            <?php else: ?>
                <p><strong>Contacto para privacidade:</strong> a disponibilizar pelo Agrupamento antes da entrada em produção.</p>
            <?php endif; ?>
        </section>
        <section>
            <h2>2. Dados pessoais tratados</h2>
            <p>A aplicação poderá tratar:</p>
            <ul>
                <li><strong>Elementos:</strong> nome, número de censo, data de nascimento, secção e observações;</li>
                <li><strong>Tutores ou encarregados de educação:</strong> nome, telefone e endereço de correio eletrónico;</li>
                <li><strong>Utilizadores e dirigentes:</strong> nome, endereço de correio eletrónico, perfil de acesso, associação a tutor e credenciais de autenticação protegidas por hash;</li>
                <li><strong>Registos técnicos:</strong> informações estritamente necessárias ao funcionamento e à segurança, caso sejam efetivamente recolhidas.</li>
            </ul>
            <p>O campo de observações não deve ser utilizado para registar dados de saúde ou outros dados sensíveis sem avaliação prévia da necessidade e das condições legais aplicáveis.</p>
        </section>
        <section>
            <h2>3. Finalidades e fundamento jurídico</h2>
            <p>Os dados destinam-se à gestão dos elementos, à associação dos respetivos tutores, à consulta de contactos autorizados, à organização por secções e à administração de acessos.</p>
            <p>O fundamento jurídico aplicável a cada finalidade deve ser determinado e documentado pela entidade responsável antes da utilização real da aplicação. A existência de uma conta ou de um registo de consentimento na base de dados não significa, por si só, que todas as operações estejam legitimadas pelo consentimento.</p>
        </section>
        <section>
            <h2>4. Acesso e partilha de informação</h2>
            <p>O acesso deve estar limitado a utilizadores autorizados, de acordo com o respetivo perfil e com a necessidade de conhecer a informação. Os dados não se destinam a divulgação pública nem a utilização comercial.</p>
            <p>Qualquer comunicação a terceiros, prestadores de alojamento ou outras entidades deve ser avaliada e documentada pela entidade responsável, incluindo eventuais obrigações contratuais e transferências internacionais.</p>
        </section>
        <section>
            <h2>5. Conservação dos dados</h2>
            <p>Os dados devem ser conservados apenas durante o período necessário às finalidades definidas e às obrigações legais aplicáveis. Os prazos concretos, bem como os procedimentos de atualização, arquivo e eliminação, devem ser definidos pelo responsável pelo tratamento antes da entrada em produção.</p>
        </section>
        <section>
            <h2>6. Segurança</h2>
            <p>Devem ser aplicadas medidas adequadas ao risco, nomeadamente autenticação, controlo de permissões, proteção de palavras-passe, ligações seguras (HTTPS), cópias de segurança protegidas e atualizações de segurança. Estas medidas têm de ser efetivamente implementadas e verificadas; a sua menção nesta política não constitui prova de implementação.</p>
        </section>
        <section>
            <h2>7. Direitos dos titulares</h2>
            <p>Nos termos do Regulamento Geral sobre a Proteção de Dados (RGPD), os titulares podem exercer, quando aplicável, os direitos de informação, acesso, retificação, apagamento, limitação, oposição e portabilidade. Podem também retirar o consentimento quando o tratamento nele se basear, sem afetar a licitude do tratamento anterior.</p>
            <p>Os pedidos relativos a menores devem ser tratados considerando as regras de representação e os direitos aplicáveis. Os pedidos devem ser dirigidos ao contacto oficial de privacidade indicado pelo Agrupamento.</p>
        </section>
        <section>
            <h2>8. Cookies e sessões</h2>
            <p>O ScoutGest poderá utilizar cookies técnicos ou identificadores de sessão necessários à autenticação e ao funcionamento da aplicação. A utilização efetiva de cookies, serviços externos ou tecnologias de análise deve ser verificada antes da publicação, com informação adicional e mecanismos de consentimento quando legalmente exigidos.</p>
        </section>
        <section>
            <h2>9. Reclamações</h2>
            <p>Sem prejuízo de outros meios de tutela, os titulares têm o direito de apresentar reclamação à <a href="https://www.cnpd.pt/" target="_blank" rel="noopener noreferrer">Comissão Nacional de Proteção de Dados (CNPD)</a>, autoridade de controlo em Portugal.</p>
        </section>
        <section>
            <h2>10. Alterações à política</h2>
            <p>Esta política poderá ser revista para refletir alterações à aplicação, às práticas de tratamento ou às obrigações legais. A data de atualização será indicada no início da página.</p>
        </section>
        <section>
            <div class="privacy-note"><strong>Nota:</strong> Este texto é uma base informativa para o projeto ScoutGest. Antes da utilização com dados reais, o responsável pelo tratamento deverá validar a política, identificar o contacto oficial, definir os fundamentos jurídicos e prazos de conservação e confirmar as medidas técnicas efetivamente implementadas.</div>
        </section>
    </article>
    <footer class="privacy-footer">&copy; <?= date('Y') ?> ScoutGest · <?= e($nomeAgrupamento) ?></footer>
</main>
</body>
</html>
