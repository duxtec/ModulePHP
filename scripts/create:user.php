#!/usr/bin/env php
<?php

use Resources\Utils\Shell;
use Resources\Manager\User;
use Resources\Database\ORM;

// Define um manipulador de erros personalizado
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (0 === error_reporting()) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    global $argv, $M;
    $username = isset($argv[1]) ? $argv[1] : null;
    $e = Shell::getOption(["e", "email"]);
    $p = Shell::getOption(["p", "password"]);
    $u = Shell::getOption(["u", "userlevel"]);
    $a = Shell::getOption(["a", "active"]);

    if (!$username) {
        $username = readline("Enter username:");
    }

    if (!$e) {
        $e = readline("Enter email [$username@modulephp.com]:") ?: "$username@modulephp.com";
    }

    if (!$p) {
        $p = readline("Enter password [P@ssw0rd]:") ?: "P@ssw0rd";
    }

    if (!$u) {
        $u = readline("Enter userlevel [1]:") ?: 1;
    }

    if (!$a) {
        $a = readline("Is user active? [1]:") ?: 1;
    }

    if (!isset($username)) {
        throw new Exception("Username not provided.");
    }

    Shell::printInfo("Inserting user '$username' into database...");
    $M->entityManager = ORM::createEntityManager();
    $response = User::create($username, $e, $p, $u, $a);

    if (!$response["success"]) {
        Shell::printError("Error inserting user '$username' into database.");
        Shell::printError($response["error"]);
        die();
    }
    Shell::printSuccess("User created successfully.");
} catch (Exception $e) {
    Shell::printError("Error creating user.");
    Shell::printError("{$e->getMessage()} in {$e->getFile()}, line {$e->getLine()}");
}