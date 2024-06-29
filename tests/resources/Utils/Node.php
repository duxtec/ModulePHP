<?php

namespace Resources\Utils;

class Node
{
    public static function isInstalled()
    {
        $output = shell_exec('node -v');
        return !empty($output);
    }

    public static function build()
    {
        if (self::isInstalled()) {
            $frontendDir = __DIR__ . '/../../resources/frontend';
            $exec = "npm run build";
            chdir($frontendDir);
            echo shell_exec($exec);
        } else {
            echo "Node.js não está instalado.\n";
        }
    }

    public static function lighthouse($url = "http://localhost:93", $outputDir = "./logs/lighthouse")
    {
        if (self::isInstalled()) {
            $frontendDir = __DIR__ . '/../../resources/frontend';

            $currentDateTime = date('Ymd/H-i');
            $outputPath = "$outputDir/$currentDateTime.html";

            $outputDirectory = dirname($outputPath);

            // Cria o diretório se não existir
            if (!file_exists($outputDirectory) && !is_dir($outputDirectory)) {
                mkdir($outputDirectory, 0755, true);
            }

            $exec = "npx resources/frontend/node_modules/lighthouse $url  --output-path=$outputPath --view";
            #chdir($frontendDir);
            shell_exec($exec);
        } else {
            echo "Node.js não está instalado.\n";
        }
    }
}