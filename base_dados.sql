-- ============================================================
-- ScoutGest V1.0 — 
-- UC00615 · Stack A — PHP + PDO + MySQL/MariaDB
-- ============================================================

SET NAMES utf8mb4;
DROP DATABASE IF EXISTS scoutgest;
CREATE DATABASE scoutgest CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE scoutgest;

-- Tutores / encarregados de educação
CREATE TABLE tutores (
    id_tutor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE
);

-- Utilizadores da aplicação. Um utilizador comum pode ficar associado a um tutor.
CREATE TABLE utilizadores (
    id_utilizador INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    consentimento_em DATETIME NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tipo ENUM('Utilizador', 'Dirigente') NOT NULL DEFAULT 'Utilizador',
    id_tutor INT NULL,
    CONSTRAINT fk_utilizadores_tutores FOREIGN KEY (id_tutor)
        REFERENCES tutores (id_tutor) ON DELETE SET NULL
);

-- Cada elemento pertence a um tutor principal (relação Tutor 1:N Elementos)
CREATE TABLE elementos (
    id_elemento INT AUTO_INCREMENT PRIMARY KEY,
    id_tutor INT NOT NULL,
    nome VARCHAR(120) NOT NULL,
    numero_censo VARCHAR(20) NOT NULL UNIQUE,
    data_nascimento DATE NULL,
    seccao VARCHAR(30) NOT NULL,
    observacoes TEXT NULL,
    CONSTRAINT fk_elementos_tutores FOREIGN KEY (id_tutor)
        REFERENCES tutores (id_tutor) ON DELETE RESTRICT
);

-- Validação de data de nascimento para não permitir datas futuras ao criar um elemento
DELIMITER $$

CREATE TRIGGER validar_data_nascimento_insert
BEFORE INSERT ON elementos
FOR EACH ROW
BEGIN
    IF NEW.data_nascimento > CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'A data de nascimento não pode ser futura.';
    END IF;
END$$

DELIMITER ;

-- Validação de data de nascimento para não permitir datas futuras ao atualizar um elemento
DELIMITER $$

CREATE TRIGGER validar_data_nascimento_update
BEFORE UPDATE ON elementos
FOR EACH ROW
BEGIN
    IF NEW.data_nascimento > CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'A data de nascimento não pode ser futura.';
    END IF;
END$$

DELIMITER ;


INSERT INTO tutores (nome, telefone, email) VALUES
('João Ferreira', '912345601', 'joao.ferreira@example.com'),
('Ana Martins', '913456702', 'ana.martins@example.com'),
('Pedro Almeida', '914567803', 'pedro.almeida@example.com'),
('Sofia Rodrigues', '915678904', 'sofia.rodrigues@example.com'),
('Ricardo Costa', '916789105', 'ricardo.costa@example.com'),
('Marta Oliveira', '917890206', 'marta.oliveira@example.com'),
('Miguel Sousa', '918901307', 'miguel.sousa@example.com'),
('Carla Pereira', '919012408', 'carla.pereira@example.com'),
('Bruno Ribeiro', '912123509', 'bruno.ribeiro@example.com'),
('Inês Carvalho', '913234610', 'ines.carvalho@example.com'),
('Tiago Lopes', '914345711', 'tiago.lopes@example.com'),
('Patrícia Gomes', '915456812', 'patricia.gomes@example.com'),
('André Teixeira', '916567913', 'andre.teixeira@example.com'),
('Daniela Moreira', '917678114', 'daniela.moreira@example.com'),
('Filipe Fernandes', '918789215', 'filipe.fernandes@example.com');


INSERT INTO elementos
(id_tutor, nome, numero_censo, data_nascimento, seccao, observacoes)
VALUES
-- Família 1: João Ferreira (3 irmãos)
(1, 'Lucas Ferreira', '676001', '2018-03-12', 'Lobitos', 'Irmão de Mariana e Diogo'),
(1, 'Mariana Ferreira', '676002', '2014-07-25', 'Exploradores', 'Irmã de Lucas e Diogo'),
(1, 'Diogo Ferreira', '676003', '2010-11-08', 'Pioneiros', 'Irmão de Lucas e Mariana'),

-- Família 2: Ana Martins (2 irmãos)
(2, 'Beatriz Martins', '676004', '2017-05-16', 'Lobitos', 'Irmã de Rodrigo'),
(2, 'Rodrigo Martins', '676005', '2013-09-21', 'Exploradores', 'Irmão de Beatriz'),

-- Família 3: Pedro Almeida (3 irmãos)
(3, 'Tomás Almeida', '676006', '2019-01-14', 'Lobitos', 'Irmão de Leonor e Rafael'),
(3, 'Leonor Almeida', '676007', '2015-04-30', 'Exploradores', 'Irmã de Tomás e Rafael'),
(3, 'Rafael Almeida', '676008', '2009-08-19', 'Pioneiros', 'Irmão de Tomás e Leonor'),

-- Família 4: Sofia Rodrigues (2 irmãos)
(4, 'Matilde Rodrigues', '676009', '2016-02-11', 'Lobitos', 'Irmã de Gustavo'),
(4, 'Gustavo Rodrigues', '676010', '2012-06-05', 'Exploradores', 'Irmão de Matilde'),

-- Família 5: Ricardo Costa (3 irmãos)
(5, 'Francisca Costa', '676011', '2018-10-02', 'Lobitos', 'Irmã de Afonso e Carolina'),
(5, 'Afonso Costa', '676012', '2013-12-17', 'Exploradores', 'Irmão de Francisca e Carolina'),
(5, 'Carolina Costa', '676013', '2008-03-28', 'Caminheiros', 'Irmã de Francisca e Afonso'),

-- Marta Oliveira
(6, 'Duarte Oliveira', '676014', '2017-08-09', 'Lobitos', NULL),
(6, 'Catarina Oliveira', '676015', '2014-01-22', 'Exploradores', NULL),
(6, 'Henrique Oliveira', '676016', '2010-05-13', 'Pioneiros', NULL),

-- Miguel Sousa
(7, 'Gabriel Sousa', '676017', '2018-06-18', 'Lobitos', NULL),
(7, 'Mafalda Sousa', '676018', '2012-11-03', 'Exploradores', NULL),
(7, 'Eduardo Sousa', '676019', '2007-09-27', 'Caminheiros', NULL),

-- Carla Pereira
(8, 'Salvador Pereira', '676020', '2019-02-24', 'Lobitos', NULL),
(8, 'Bárbara Pereira', '676021', '2015-10-07', 'Exploradores', NULL),
(8, 'Simão Pereira', '676022', '2011-04-15', 'Pioneiros', NULL),

-- Bruno Ribeiro
(9, 'Vicente Ribeiro', '676023', '2017-12-04', 'Lobitos', NULL),
(9, 'Alice Ribeiro', '676024', '2013-03-19', 'Exploradores', NULL),
(9, 'Martim Ribeiro', '676025', '2008-07-11', 'Caminheiros', NULL),

-- Inês Carvalho
(10, 'Laura Carvalho', '676026', '2018-09-26', 'Lobitos', NULL),
(10, 'Daniel Carvalho', '676027', '2014-06-14', 'Exploradores', NULL),
(10, 'Teresa Carvalho', '676028', '2010-02-08', 'Pioneiros', NULL),

-- Tiago Lopes
(11, 'Francisco Lopes', '676029', '2016-05-23', 'Lobitos', NULL),
(11, 'Joana Lopes', '676030', '2012-08-12', 'Exploradores', NULL),
(11, 'Nuno Lopes', '676031', '2007-11-29', 'Caminheiros', NULL),

-- Patrícia Gomes
(12, 'Maria Gomes', '676032', '2019-04-06', 'Lobitos', NULL),
(12, 'Gonçalo Gomes', '676033', '2015-01-31', 'Exploradores', NULL),

-- André Teixeira
(13, 'Santiago Teixeira', '676034', '2017-07-20', 'Lobitos', NULL),
(13, 'Clara Teixeira', '676035', '2011-09-10', 'Pioneiros', NULL),

-- Daniela Moreira
(14, 'Eva Moreira', '676036', '2018-11-15', 'Lobitos', NULL),
(14, 'David Moreira', '676037', '2013-05-02', 'Exploradores', NULL),
(14, 'Rita Moreira', '676038', '2009-12-09', 'Pioneiros', NULL),

-- Filipe Fernandes
(15, 'Miguel Fernandes', '676039', '2016-08-27', 'Lobitos', NULL),
(15, 'Sara Fernandes', '676040', '2008-04-18', 'Caminheiros', NULL);


-- Contas de teste (password: Demo1234)
-- Dirigente: acesso total à gestão
INSERT INTO utilizadores (nome, email, password_hash, consentimento_em, tipo, id_tutor) VALUES
('DirTeste', 'dirteste@scoutgest.pt', '$2y$12$wtvyr7Br21NfSwx8lFD1EujFE1Q/5l4NumFPTF4aZ5OVbvUr4/V2m', NOW(), 'Dirigente', NULL),
('Daniela Moreira', 'danmor@userteste.pt', '$2y$12$wtvyr7Br21NfSwx8lFD1EujFE1Q/5l4NumFPTF4aZ5OVbvUr4/V2m', NOW(), 'Utilizador', 14);
