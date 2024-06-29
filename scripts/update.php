<?php

function copy_dir($diretory, $destination, $ver_acao = false)
{
    if ($destination[strlen($destination) - 1] == '/') {
        $destination = substr($destination, 0, -1);
    }
    if (!is_dir($destination)) {
        if ($ver_acao) {
            echo "Creating directory {$destination}\n";
        }
        mkdir($destination, 0755);
    }

    $folder = opendir($diretory);

    while ($item = readdir($folder)) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (is_dir("{$diretory}/{$item}")) {
            copy_dir("{$diretory}/{$item}", "{$destination}/{$item}", $ver_acao);
        } else {
            if ($ver_acao) {
                echo "Copying {$item} to {$destination}" . "\n";
            }
            copy("{$diretory}/{$item}", "{$destination}/{$item}");
        }
    }
}

$path = dirname(dirname(__FILE__));

#Backup resources folder
echo "Preparing backup of resources folder: ";
copy_dir("$path/resources", "$path/resources.bak", true);
echo "Backup completed.";
#Download updated resources.