# ScoutGest

**Sistema de Gestão de Elementos e Tutores — Agrupamento 676 Cristo-Rei**

Projeto desenvolvido no âmbito do **CET 25.0462 — UC00615**, utilizando a **Stack A (PHP, PDO e MySQL/MariaDB)**.

## Sobre o projeto

O ScoutGest é uma aplicação web para organizar os dados dos elementos do agrupamento de escuteiros e dos respetivos tutores (encarregados de educação). Centraliza contactos, dados de identificação e relações familiares, substituindo a consulta de registos em papel por uma interface web.

## Funcionalidades

- Registo de contas com consentimento de privacidade e autenticação por email e palavra-passe.
- Dois perfis de acesso: **Utilizador** e **Dirigente**.
- Consulta da área pessoal e dos dados associados à conta.
- Gestão de elementos: listagem, pesquisa, criação, edição, consulta e eliminação (operações administrativas reservadas a dirigentes).
- Gestão de tutores: consulta, criação, edição e eliminação, de acordo com as permissões da aplicação.
- Gestão de utilizadores por dirigentes: pesquisa, filtros por perfil e estado de associação, alteração de perfil e associação de contas a tutores.
- Associação de vários elementos a um tutor.
- Página de política de privacidade e interface adaptável a diferentes ecrãs.

## Tecnologias

| Camada | Tecnologia |
| --- | --- |
| Backend | PHP |
| Base de dados | MySQL / MariaDB |
| Acesso a dados | PDO e consultas preparadas |
| Frontend | HTML, CSS e JavaScript |
| Ambiente local | XAMPP (Apache e MySQL/MariaDB) |

## Instalação local

1. Instalar o **XAMPP** com PHP e MySQL/MariaDB.
2. Extrair o projeto para `C:\xampp\htdocs\scoutgest` (ou outra pasta dentro de `htdocs`).
3. Iniciar **Apache** e **MySQL** no painel do XAMPP.
4. Abrir o **phpMyAdmin** em `http://localhost/phpmyadmin/` e importar o ficheiro `base_dados.sql` através do separador **Importar**. **Atenção:** o script contém `DROP DATABASE IF EXISTS scoutgest`, pelo que elimina uma base de dados com esse nome, caso já exista.
5. Confirmar as credenciais em `config/db.php`:

   ```php
   $host = 'localhost';
   $bd = 'scoutgest';
   $user = 'root';
   $pass = '';
   ```

   Estes valores são os de exemplo para XAMPP local; adaptar às credenciais do ambiente utilizado.
6. Aceder a `http://localhost/scoutgest/` (ajustar o nome da pasta, se necessário).
7. Criar uma conta em **Registar** e iniciar sessão. As permissões de dirigente têm de ser atribuídas por um administrador autorizado na base de dados ou por um dirigente existente através da gestão de utilizadores.

> **Nota:** os registos incluídos no SQL são dados de demonstração. Não utilizar a configuração de exemplo nem dados pessoais reais num servidor público sem configurar adequadamente o ambiente e os acessos.

## Estrutura do projeto

```text
scoutgest/
├── config/
│   └── db.php                 # Ligação PDO à base de dados
├── css/
│   └── style.css              # Estilos da aplicação
├── js/
│   └── validacao.js           # Validação no navegador
├── img/                       # Logótipo, ícones e favicon
├── includes/                  # Inicialização, autenticação e componentes partilhados
├── docs/                      # Página e recursos de documentação/apresentação
├── base_dados.sql             # Estrutura e dados de demonstração
├── index.php                  # Página inicial
├── login.php                  # Autenticação
├── registo.php                # Registo de conta
├── logout.php                 # Terminar sessão
├── minha_area.php             # Área pessoal
├── conta.php                  # Dados da conta
├── elementos.php              # Listagem de elementos
├── elemento_novo.php          # Novo elemento
├── elemento_editar.php        # Editar elemento
├── elemento_ver.php           # Detalhes de elemento
├── elemento_apagar.php        # Eliminar elemento
├── tutores.php                # Listagem de tutores
├── tutor_form.php             # Formulário de tutor
├── tutor_apagar.php           # Eliminar tutor
├── utilizadores.php           # Gestão de contas
├── utilizador_editar.php      # Editar perfil e associação
├── privacidade.php            # Política de privacidade
└── LICENSE                    # Licença MIT
```

## Modelo de dados

A base de dados `scoutgest` contém três tabelas principais:

| Tabela | Finalidade |
| --- | --- |
| `tutores` | Contactos dos tutores e respetivos identificadores |
| `elementos` | Identificação, número de censo, data de nascimento, secção e tutor principal |
| `utilizadores` | Contas, hash da palavra-passe, perfil, consentimento e eventual associação a tutor |

**Relações:** `tutores` 1:N `elementos`; `tutores` 1:N `utilizadores` (associação opcional nas contas). A base de dados impede datas de nascimento futuras através de triggers e utiliza chaves estrangeiras para garantir integridade referencial.

## Segurança

O código inclui mecanismos como:

- Palavras-passe guardadas com `password_hash()` e verificadas com `password_verify()`.
- Consultas parametrizadas com PDO para valores introduzidos pelos utilizadores.
- Sessões PHP e controlo de acesso por perfil.
- Tokens CSRF nos formulários de alteração de dados que os implementam.
- Validação de dados no servidor, complementada por JavaScript.
- Cabeçalhos para evitar a cache de páginas com dados pessoais.

Estas medidas não substituem uma auditoria de segurança antes de disponibilizar o sistema na Internet.

## Utilização

O perfil **Utilizador** destina-se ao acesso à conta e à informação pessoal disponibilizada pela aplicação. O perfil **Dirigente** permite aceder às áreas administrativas de elementos, tutores e utilizadores. A associação entre uma conta e um tutor é efetuada na gestão de utilizadores.

## Autoria e licença

Projeto **ScoutGest**, desenvolvido no contexto académico do CET 25.0462 para o Agrupamento 676 Cristo-Rei.

Licenciado sob a **MIT License**. Consultar [LICENSE](LICENSE) para os termos completos.
