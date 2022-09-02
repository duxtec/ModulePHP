/* 
 Tables:
 - Userlevel;
 - Users;
 - Sessions;
 
 */
CREATE DATABASE IF NOT EXISTS modulephp;
CREATE USER IF NOT EXISTS 'modulephp' @'localhost' IDENTIFIED BY 'example_not_use_this_password';
GRANT ALL PRIVILEGES ON *.* TO 'modulephp' @'localhost';
USE modulephp;
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
    userlevel_id INT UNSIGNED DEFAULT 1,
    password varchar(255),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lastchange_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    enabled BOOLEAN DEFAULT 1
);
CREATE TABLE IF NOT EXISTS Sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UserID INT UNSIGNED,
    PHPsession_id VARCHAR(255),
    UserIP VARCHAR(255),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lastchange_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
INSERT IGNORE INTO Userlevel (id, name)
VALUES (1, "admin"),
    (2, "common");
INSERT IGNORE INTO Users (
        id,
        name,
        username,
        email,
        userlevel_id,
        password
    )
VALUES (
        1,
        "Common",
        "common",
        "common@localhost",
        1,
        "$2y$10$hCc7abPBwn.d1.Jl4MWCF.alGyy9dF47Lm/6.3BozPMOG8GM0DkvC"
    ),
    (
        2,
        "Infraestrutura",
        "infra",
        "infra@localhost",
        2,
        "$2y$10$hCc7abPBwn.d1.Jl4MWCF.alGyy9dF47Lm/6.3BozPMOG8GM0DkvC"
    );