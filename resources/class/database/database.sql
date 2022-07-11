/* 
Tables:
- Userlevel;
- Users;
- Sessions;

*/

CREATE DATABASE IF NOT EXISTS lrap_os;
CREATE USER IF NOT EXISTS 'lrap_os'@'localhost' IDENTIFIED BY 'O^0tu4%wGVco301Jh&2uGR@ID!iKo^8K';
GRANT ALL PRIVILEGES ON * . * TO 'lrap_os'@'localhost';
USE lrap_os;
CREATE TABLE IF NOT EXISTS Userlevel (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Users (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255),
username VARCHAR(255),
email VARCHAR(50),
cpf VARCHAR(50),
phone VARCHAR(50),
userlevel_id INT UNSIGNED DEFAULT 1,
password varchar(255),
registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
lastchange_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
disabled BOOLEAN DEFAULT 1
);

CREATE TABLE IF NOT EXISTS Sessions (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
UserID INT UNSIGNED,
PHPsession_id VARCHAR(255),
UserIP VARCHAR(255),
registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
lastchange_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Locais (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(255),
registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
lastchange_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS OS_Categorias (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(255),
registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
lastchange_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS OSs (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(255),
descricao TEXT(3000),
local_detalhado TEXT(3000),
status VARCHAR(255) DEFAULT "Aguardando execução",
resposta TEXT(3000),
prazo DATE,
UserID INT UNSIGNED,
LocalID INT UNSIGNED,
OS_CategoriaID INT UNSIGNED,
registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
lastchange_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO Userlevel (id, name)
VALUES
(1, "comum"),
(2, "infra"),
(3, "coord");

INSERT IGNORE INTO Users (id, name, username, email, userlevel_id, password)
VALUES
(1, "Comum", "comum", "comum@localhost", 1, "$2y$10$G5OiRjQZjl2wAAW0K4vZZu0AdCitrsGi4duF0wTA3paw5VlYg.ism"),
(2, "Infraestrutura", "infra", "infra@localhost", 2, "$2y$10$G5OiRjQZjl2wAAW0K4vZZu0AdCitrsGi4duF0wTA3paw5VlYg.ism"),
(3, "Coordenação", "coord", "coord@localhost", 3, "$2y$10$G5OiRjQZjl2wAAW0K4vZZu0AdCitrsGi4duF0wTA3paw5VlYg.ism");

INSERT IGNORE INTO Locais(nome)
VALUES
("Lab. Expansão"),
("Lab. Fluidos"),
("Lab. Petrofísica"),
("Lab. Tomógrafo"),
("Lab. CoreFlood"),
("Sala Alunos"),
("Seção Administração"),
("Seção Infraestrutura"),
("Seção SMS"),
("Coordenação LRAP"),
("Sem local físico");

INSERT IGNORE INTO OS_Categorias(nome)
VALUES
("T.I. - Rede (cabeada/Wi-Fi)"),
("T.I. - Instalação"),
("T.I. - Manutenção");

INSERT IGNORE INTO OSs(
    nome,
    descricao,
    local_detalhado,
    status,
    UserID,
    LocalID,
    OS_CategoriaID
    )
VALUES
(
    "OS de teste",
    "Descrição da OS de teste",
    "Local detalhado da OS de teste",
    "Status da OS de teste",
    1, 1, 1
)