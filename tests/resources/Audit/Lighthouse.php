<?php

namespace Resources\Audit;

use Resources\Utils\Node;

class Lighthouse
{
    public static function exec($url = "http://localhost:93", $outputDir = "./logs/lighthouse")
    {
        if (Node::isInstalled()) {
            $frontendDir = __DIR__ . '/../../resources/frontend';

            $currentDateTime = date('Ymd/Hi');
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

    public static function list()
    {
        $list = array();

        // Search for HTML files in the specified directory
        $files = glob("logs/lighthouse/*/*.html");

        // Iterate through each HTML file found
        foreach ($files as $file) {
            // Get the date from the directory name
            $relativePath = str_replace('logs/lighthouse/', '', $file);
            $date = dirname($relativePath);
            $hour = pathinfo($relativePath, PATHINFO_FILENAME);

            $name = "$date$hour";

            $list[] = $name;
        }

        return $list;
    }

    public static function open($id)
    {
        $date = substr($id, 0, 8); // Extrai os primeiros 8 caracteres (a data)
        $time = substr($id, 8);    // Extrai o restante dos caracteres (a hora)

        // Construir o caminho completo do arquivo com base na data e hora fornecidas
        $filePath = "logs/lighthouse/{$date}/{$time}.html";

        // Verificar se o arquivo existe
        if (file_exists($filePath)) {
            // Ler e retornar o conteúdo do arquivo
            return file_get_contents($filePath);
        } else {
            // Se o arquivo não existir, retornar null ou lançar uma exceção, dependendo da sua lógica
            return null;
        }
    }
}