#!/usr/bin/env php
<?php

use Resources\Config\Config;
use Resources\Utils\Color;
use Resources\Utils\Shell;
use Resources\Manager\User;

header("Content-Type: text/css;X-Content-Type-Options: nosniff;");

#Function to create color palette with the colors defined in the site settings.
function palette()
{
    $path = dirname(dirname(dirname(dirname(__FILE__))));

    $site = (new Config())->site;
    $color1 = $site->COLOR1;
    $color2 = $site->COLOR2;

    $hsl1 = Color::RGBtoHSL($color1, true);
    $hsl2 = Color::RGBtoHSL($color2, true);

    $darkcolor = "--darkcolor0-1: #000;" . PHP_EOL;
    $lightcolor = "--lightcolor0-1: #FFF;" . PHP_EOL;
    $brightcolor = "";

    for ($i = 5; $i < 30; $i += 5) {
        $hsl1[2] = $i;
        $hsl2[2] = $i;
        $n = ($i) / 5;
        $darkcolor1 = Color::WebSafe(Color::HSLtoRGB($hsl1));
        $darkcolor2 = Color::WebSafe(Color::HSLtoRGB($hsl2));
        $darkcolor .= "--darkcolor1-$n: $darkcolor1;" . PHP_EOL;
        $darkcolor .= "--darkcolor2-$n: $darkcolor2;" . PHP_EOL;
        $hsl1[2] = 100 - $i;
        $hsl2[2] = 100 - $i;
        $lightcolor1 = Color::WebSafe(Color::HSLtoRGB($hsl1));
        $lightcolor2 = Color::WebSafe(Color::HSLtoRGB($hsl2));
        $lightcolor .= "--lightcolor1-$n: $lightcolor1;" . PHP_EOL;
        $lightcolor .= "--lightcolor2-$n: $lightcolor2;" . PHP_EOL;
        $hsl1[2] = 50;
        $hsl2[2] = 50;
        $old_s1 = $hsl1[1];
        $old_s2 = $hsl2[1];
        $hsl1[1] = 115 - ($i + 10 * $n);
        $hsl2[1] = 115 - ($i + 10 * $n);
        $brightcolor1 = Color::WebSafe(Color::HSLtoRGB($hsl1));
        $brightcolor2 = Color::WebSafe(Color::HSLtoRGB($hsl2));
        $hsl2[1] = $old_s1;
        $hsl2[1] = $old_s2;
        $brightcolor .= "--brightcolor1-$n: $brightcolor1;" . PHP_EOL;
        $brightcolor .= "--brightcolor2-$n: $brightcolor2;" . PHP_EOL;
    }
    $root = <<<content
    :root {
    $darkcolor
    $lightcolor
    $brightcolor
    --boxshadow1: 0 0 10px 2px rgb(0 0 0 / 0.3)
    }
    content;
    return $root;
}

$palette = palette();

$u = isset($argv[1]) ? $argv[1] : "global";

$path = dirname(dirname(__FILE__)) . "/app/$u/assets/src/css/palette.css";

$directory = dirname($path);
if (!is_dir($directory)) {
    mkdir($directory, 0777, true);
}

file_put_contents($path, $palette);

if (file_exists($path)) {
    Shell::printSuccess("Palette written to file: $path\n");
} else {
    Shell::printError("Failed to write palette to file: $path\n");
}