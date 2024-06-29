/* 
 Tables:
 - Userlevel;
 - Users;
 - Sessions;
 
 */
CREATE DATABASE IF NOT EXISTS appgrade_netprev;
CREATE USER IF NOT EXISTS 'appgrade_netprev' @'localhost' IDENTIFIED BY 'B5F3jg62MK0c';
GRANT ALL PRIVILEGES ON *.* TO 'appgrade_netprev' @'localhost';
USE appgrade_netprev;
CREATE TABLE IF NOT EXISTS Userlevel (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS Users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    email VARCHAR(50),
    password varchar(255),
    active BOOLEAN DEFAULT 1,
    userlevel_id INT UNSIGNED DEFAULT 1,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lastchange_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS Sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UserID INT UNSIGNED,
    PHPsession_id VARCHAR(255),
    UserIP VARCHAR(255),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lastchange_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE Clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    cpf CHAR(11) UNIQUE NOT NULL,
    nome_mae VARCHAR(255),
    contato1 VARCHAR(20),
    contato2 VARCHAR(20),
    cep CHAR(8),
    logradouro VARCHAR(255),
    numero VARCHAR(10),
    complemento VARCHAR(255),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    uf CHAR(2),
    tipo_servico VARCHAR(50),
    senha_site_inss VARCHAR(255),
    descricao_duvida TEXT FOREIGN KEY (user_id) REFERENCES Users(id)
);
CREATE TABLE Cliente_User (
    cliente_id INT NOT NULL PRIMARY KEY,
    user_id INT NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES Clientes(id),
    FOREIGN KEY (user_id) REFERENCES Users(id)
);
CREATE TABLE Agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data DATE NOT NULL,
    hora TIME NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES Cliente(id)
);
CREATE TABLE ChatMessages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message_text TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES Users(id),
    -- assumindo que existe uma tabela Users com a coluna id
    FOREIGN KEY (receiver_id) REFERENCES Users(id) -- assumindo que existe uma tabela Users com a coluna id
);
INSERT IGNORE INTO Userlevel (id, name)
VALUES (1, "cliente"),
    (2, "operador"),
    (3, "admin");
INSERT IGNORE INTO Users (
        id,
        username,
        email,
        userlevel_id,
        password
    )
VALUES (
        1,
        "123456789",
        "cliente@localhost",
        1,
        "$2y$10$MpoQFr3gmaJEq4m78n073.gCK0PoVu0zRHW/aEjj/4JlsayU2iil."
    ),
    (
        2,
        "operador",
        "operador@localhost",
        2,
        "$2y$10$MpoQFr3gmaJEq4m78n073.gCK0PoVu0zRHW/aEjj/4JlsayU2iil."
    ),
    (
        3,
        "admin",
        "admin@localhost",
        3,
        "$2y$10$MpoQFr3gmaJEq4m78n073.gCK0PoVu0zRHW/aEjj/4JlsayU2iil."
    );