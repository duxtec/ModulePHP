<?php

namespace Resources\Utils;

use PhpSchool\CliMenu\Builder\CliMenuBuilder;

class Shell
{
    public static function getOption($options, $default = null)
    {
        global $argv;

        // Prepara um array com todas as opções possíveis, tanto curtas quanto longas
        $allOptions = [];
        foreach ($options as $option) {
            $allOptions[] = '-' . $option;
            $allOptions[] = '--' . $option;
        }
        // Percorre os argumentos da linha de comando
        for ($i = 1; $i < count($argv); $i++) {
            // Verifica se o argumento corresponde a uma das opções possíveis
            if (in_array($argv[$i], $allOptions)) {
                // Retorna o valor da opção, se houver
                $valueIndex = array_search($argv[$i], $argv);
                if (isset($argv[$valueIndex + 1]) && !in_array($argv[$valueIndex + 1], $allOptions)) {
                    return $argv[$valueIndex + 1];
                } else {
                    return true; // Se não houver valor, retorna true
                }
            }
        }

        // Retorna o valor padrão se a opção não for encontrada
        return $default;
    }

    public static function getAllOptions()
    {
        global $argv;
        $args = $argv;
        $default = Shell::getDefaultOption();

        $parsedArgs = ['default' => $default];

        $i = 0;
        while ($i < count($args) - 1) {
            $arg = $args[$i];

            if (in_array($arg, ["php", "allay", "-v", "--verbose"])) {
                $i++;
                continue;
            }

            // Se o argumento começar com '-' ou '--'
            if (substr($arg, 0, 1) === '-') {
                $param = ltrim($arg, '-'); // Remove o '-' ou '--'
                $value = true; // Valor padrão

                // Verifica se o próximo argumento é um valor ou outro parâmetro
                if ($i + 1 < count($args) && substr($args[$i + 1], 0, 1) !== '-') {
                    $value = $args[$i + 1]; // Define o valor do parâmetro
                    $i++; // Avança para o próximo argumento
                }

                // Adiciona o parâmetro e seu valor ao array
                $parsedArgs[$param] = $value;
            }

            $i++; // Avança para o próximo argumento
        }

        return $parsedArgs;
    }

    public static function getDefaultOption()
    {
        global $argv;

        if (empty($argv)) {
            return null;
        }
        $index = 0;
        while ($index < count($argv)) {
            $arg = $argv[$index];

            if (in_array($arg, ["php", "allay", "-v", "--verbose"])) {
                $index++;
                continue;
            }

            if (strlen($arg) === 2 && $arg[0] === '-' && ctype_alpha($arg[1])) {
                $index++;
                break;
            }

            if (strlen($arg) > 3 && substr($arg, 0, 2) == "--" && ctype_alpha($arg[2])) {
                $index++;
                break;
            }

            return $arg;
        }

        return null;
    }

    public static function isVerboseMode()
    {
        global $argv;

        // Se não houver argumentos na linha de comando, retorne false
        if (empty($argv)) {
            return false;
        }

        // Verifique se algum dos argumentos é "-v" ou "--verbose"
        foreach ($argv as $arg) {
            if ($arg === '-v' || $arg === '--verbose') {
                return true;
            }
        }

        // Se nenhum dos argumentos for "-v" ou "--verbose", retorne false
        return false;
    }


    public static function print($string, $color = null)
    {

        // Mapeamento de cores em hexadecimal para códigos de formatação ANSI
        $colors = array(
            'black' => '30',
            'red' => '31',
            'green' => '32',
            'yellow' => '33',
            'blue' => '34',
            'magenta' => '35',
            'cyan' => '36',
            'light_gray' => '37',
            'gray' => '90',
            'light_red' => '91',
            'light_green' => '92',
            'light_yellow' => '93',
            'light_blue' => '94',
            'light_magenta' => '95',
            'light_cyan' => '96',
            'white' => '97',
        );

        // Verifica se a cor fornecida está no array de cores e obtém o código ANSI correspondente
        $ansiColor = isset($colors[$color]) ? $colors[$color] : null;

        // Se a cor estiver definida, imprime a string com a formatação ANSI
        if ($ansiColor !== null) {
            echo "\033[{$ansiColor}m{$string}\033[0m";

        } else {
            // Se a cor não estiver definida, imprime a string sem formatação de cor
            echo $string;
        }
    }
    public static function printError($string)
    {
        self::print("$string\n", "light_red");
    }

    public static function printAlert($string)
    {
        self::print("$string\n", "yellow");
    }
    public static function printSuccess($string)
    {
        self::print("$string\n", "light_green");
    }

    public static function printInfo($string)
    {
        self::print("$string\n", "light_blue");
    }

    public static function clear()
    {
        // Verifica o sistema operacional
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows
            system('cls');
        } else {
            // Unix-like (Linux, macOS)
            system('clear');
        }
    }

    public static function showMenu($title, $options)
    {
        $menuBuilder = (new CliMenuBuilder)
            ->setTitle($title)
            ->setForegroundColour(5)
            ->setBackgroundColour(0);

        foreach ($options as $option => $value) {
            if (is_array($options)) {
                $menuBuilder->addItem($value, function () use ($option) {
                    self::closeMenu();
                    return $option;
                });
            } else {
                $menuBuilder->addItem($value, function () use ($value) {
                    self::closeMenu();
                    return $value;
                });
            }
        }

        global $menu;
        $menu = $menuBuilder->build();

        // Wait for user input and return the selected option
        $menu->open();
        return $menu->getSelectedItem()->getText();
    }


    public static function closeMenu()
    {
        global $menu;
        if ($menu) {
            $menu->close();
        }
    }

}