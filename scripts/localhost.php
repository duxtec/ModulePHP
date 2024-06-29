#!/usr/bin/env php
<?php

use Resources\Utils\Shell;

global $M;

$options = Shell::getAllOptions();
$h = $options["h"] ?? $options["host"] ?? "0.0.0.0";
$p = $options["p"] ?? $options["port"] ?? "93";
$r = $options["r"] ?? $options["root"] ?? dirname(dirname(__FILE__)) . "/public_html";
$b = $options["b"] ?? $options["build"] ?? true;
$m = $options["m"] ?? $options["mode"] ?? "node";
$s = $options["s"] ?? $options["sudo"] ?? false;

if ($M->Config->system->PRODUCTION && $b && !$s) {
    require_once "scripts/build.php";
}

$exec = "php -S $h:$p public_html/index.php 2>&1";

$url = $h == "0.0.0.0" ? "localhost:$p" : "$h:$p";

Shell::printSuccess("Server started at http://$url");
Shell::printInfo("Press Ctrl+C to shut down the server.");

$output = system($exec);

// Verifica se o servidor foi iniciado com sucesso
if (strpos($output, 'Failed to listen') !== false) {
    // Se houver algum erro ao iniciar o servidor, exibe a saída do comando
    Shell::clear();
    Shell::printError("Error starting server.");
    Shell::printError($output);

    if (!$s) {
        Shell::printInfo("To try to start as root, enter the password:");
        global $argv;
        $args = implode(' ', $argv);
        $output = system("sudo su -c \"php allay localhost $args --sudo\"");
    }
}