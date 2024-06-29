<?php

namespace Resources\Utils;

use Resources\Config\Config;

class Color
{
    /**
     * Summary of RGBtoHSL
     * @param mixed $rgb
     * @param mixed $array
     * @return array<float>|string
     */
    public static function RGBtoHSL($rgb, $array = false)
    {
        if (strlen($rgb) == 7) {
            $r = hexdec("$rgb[1]$rgb[2]");
            $g = hexdec("$rgb[3]$rgb[4]");
            $b = hexdec("$rgb[5]$rgb[6]");
        } else {
            $r = hexdec("$rgb[1]$rgb[1]");
            $g = hexdec("$rgb[2]$rgb[2]");
            $b = hexdec("$rgb[3]$rgb[3]");
        }

        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $l = ($max + $min) / 2;
        $d = $max - $min;

        if ($d == 0) {
            $h = $s = 0; // achromatic
        } else {
            $s = $d / (1 - abs(2 * $l - 1));

            switch ($max) {
                case $r:
                    $h = 60 * fmod((($g - $b) / $d), 6);
                    if ($b > $g) {
                        $h += 360;
                    }
                    break;

                case $g:
                    $h = 60 * (($b - $r) / $d + 2);
                    break;

                case $b:
                    $h = 60 * (($r - $g) / $d + 4);
                    break;
            }
        }
        $h = round($h);
        $s = round($s, 2) * 100;
        $l = round($l, 2) * 100;

        if ($array) {
            return [$h, $s, $l];
        }
        return "hsl($h,$s%,$l%)";
    }

    /**
     * Summary of HSLtoRGB
     * @param mixed $hsl
     * @param mixed $array
     * @return array<float>|string
     */
    public static function HSLtoRGB($hsl, $array = false)
    {
        $h = $hsl[0];
        $s = $hsl[1];
        $l = $hsl[2];
        $s = $s / 100;
        $l = $l / 100;

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod(($h / 60), 2) - 1));
        $m = $l - ($c / 2);

        if ($h < 60) {
            $r = $c;
            $g = $x;
            $b = 0;
        } elseif ($h < 120) {
            $r = $x;
            $g = $c;
            $b = 0;
        } elseif ($h < 180) {
            $r = 0;
            $g = $c;
            $b = $x;
        } elseif ($h < 240) {
            $r = 0;
            $g = $x;
            $b = $c;
        } elseif ($h < 300) {
            $r = $x;
            $g = 0;
            $b = $c;
        } else {
            $r = $c;
            $g = 0;
            $b = $x;
        }

        $r = round(($r + $m) * 255);
        $g = round(($g + $m) * 255);
        $b = round(($b + $m) * 255);

        if ($array) {
            return [$r, $g, $b];
        }

        $r = str_pad(dechex($r), 2, 0, STR_PAD_LEFT);
        $g = str_pad(dechex($g), 2, 0, STR_PAD_LEFT);
        $b = str_pad(dechex($b), 2, 0, STR_PAD_LEFT);
        return "#$r$g$b";
    }

    public static function WebSafe($rgb, $array = false)
    {
        $r = hexdec("$rgb[1]$rgb[2]");
        $g = hexdec("$rgb[3]$rgb[4]");
        $b = hexdec("$rgb[5]$rgb[6]");

        $r = round($r / 17);
        $g = round($g / 17);
        $b = round($b / 17);

        if ($array) {
            $r = $r * 17;
            $g = $g * 17;
            $b = $b * 17;
            return [$r, $g, $b];
        }

        $r = dechex($r);
        $g = dechex($g);
        $b = dechex($b);
        return "#$r$g$b";
    }

    public static function palette()
    {
        $path = dirname(dirname(__FILE__));
        require_once ("$path/resources/class/config.php");

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
        --boxshadow1: 0 0 5px 5px rgb(0 0 0 / 0.3);
        }
        content;
        return $root;
    }
}