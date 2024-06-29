#!/usr/bin/env php
<?php

use Resources\Utils\Shell;

global $M;

$Configs = [
    "site" => [
        "NAME" => "Module PHP",
        "DESCRIPTION" => "Description of Module PHP",
        "LANG" => "pt-BR",
        "TWITTER" => "",
        "INSTAGRAM" => "",
        "FACEBOOK" => "",
        "PHONE" => "",
        "COLOR1" => "#2E3094",
        "COLOR2" => "#F7991F",
        "AUTHOR" => "Dux Tecnologia - https://dux.tec.br",
        "URL" => "https://modulephp.com",
        "LOGO" => "/assets/img/logo.webp",
        "ICON" => "/assets/img/icon.webp",
    ],
    "database" => [
        "TYPE" => "mysql",
        "HOST" => "localhost",
        "PORT" => "3306",
        "NAME" => "modulephp",
        "USER" => "modulephp",
        "PW" => false,
    ],
    "mail" => [
        "TYPE" => "phpmailer",
        "HOST" => "localhost",
        "SMTPPORT" => "465",
        "IMAPPORT" => "993",
        "POP3PORT" => "995",
        "USER" => "noreply@modulephp.com",
        "PW" => false,
        "NAME" => "Module PHP",
    ],
    "system" => [
        "PRODUCTION" => false,
        "LOADER" => false,
        "PWA" => true,
    ],
    "build" => [
        "TIMESTAMP" => time(),
    ],
    "recaptcha" => [
        "SECRET_KEY" => false,
        "PUBLIC_KEY" => false,
    ],
    "message" => [
        "UNAVAILABLE" => "System Unavailable",
        "PAGE_NOT_FOUND" => "404 - Page not found",
        "FILE_NOT_FOUND" => "File not found. Try logging in again.",
    ],
];

foreach ($Configs as $name => $data) {
    createConfigFile($name, $data);
}

function createConfigFile($name, $data)
{
    $title = ucfirst($name);
    $fileName = "$name.env";

    global $M;
    Shell::printInfo("Creating $fileName file...\n");
    $filePath = dirname(dirname(__FILE__)) . "/config/$fileName";
    $fileContent = "[$title]\n";

    global $argv;
    $default = in_array("-d", $argv);

    // Verificar se existe algo preenchido em $M->Config->{$fileName}
    $existingConfig = isset($M->Config->{$name}) ? (array) $M->Config->{$name} : [];

    // Perguntar ao usuário para modificar os valores padrão, se não estiver no modo de desenvolvimento
    foreach ($data as $key => $defaultValue) {

        // Usar o valor existente se estiver disponível, caso contrário, usar o valor padrão
        $value = isset($existingConfig[$key]) && $existingConfig[$key] !== "" ? $existingConfig[$key] : $defaultValue;

        $valueToShow = $value;

        if (is_bool($value)) {
            $valueToShow = $value ? 'true' : 'false';
        }

        // Mostrar o valor padrão ao solicitar a entrada do usuário
        fwrite(STDOUT, "Enter $key [$valueToShow]: ");
        $userInput = trim(fgets(STDIN));

        // Usar o valor inserido pelo usuário, se fornecido; caso contrário, usar o valor padrão
        $value = !empty($userInput) ? $userInput : $value;

        // Adicionar a linha ao conteúdo do arquivo
        $fileContent .= "$key=$value\n";
    }

    // Escrever o conteúdo no arquivo
    file_put_contents($filePath, $fileContent);

    // Informar ao usuário que o arquivo foi criado com sucesso
    Shell::printSuccess("File $fileName created successfully.\n");
}