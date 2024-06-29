<?php

namespace Resources\Utils;
use \USERLEVEL_FOLDER;

class Asset
{
    public static function url($name = false, $width = false)
    {
        global $M;
        $path = self::pathByName($name);

        if ($M->Config->system->PRODUCTION) {
            $imageTypes = array(
                IMAGETYPE_GIF,
                IMAGETYPE_JPEG,
                IMAGETYPE_PNG,
                IMAGETYPE_WEBP,
                IMAGETYPE_BMP,
                IMAGETYPE_ICO,
                IMAGETYPE_TIFF_II,
                IMAGETYPE_TIFF_MM,
                IMAGETYPE_JPC,
                IMAGETYPE_JP2,
                IMAGETYPE_JPX,
                IMAGETYPE_JB2,
                IMAGETYPE_SWC,
                IMAGETYPE_IFF,
                IMAGETYPE_WBMP,
                IMAGETYPE_XBM
            );
            #die($path);
            if (!empty($path)) {
                if (in_array(exif_imagetype($path), $imageTypes)) {
                    $name = preg_replace('/\.\w+$/i', '.webp', $name);
                    $name = $width ? preg_replace('/\.webp$/', "_{$width}.webp", $name) : $name;
                }
            }
            return "/assets/{$M->Config->build->TIMESTAMP}/$name";
        } else {
            return "/assets/$name";
        }
    }

    public static function path($url = false)
    {
        if (!$url) {
            $url = explode("?", substr($_SERVER["REQUEST_URI"], 1))[0];
            $url = preg_replace('/^controller/', '', $url);
        }

        global $M;

        if ($M->Config->system->PRODUCTION) {
            $path = preg_replace('/^assets/', 'assets/build', $url);
        } else {
            $path = preg_replace('/^assets/', 'assets/src', $url);
        }


        $userlevel = defined('USERLEVEL') ? USERLEVEL : "global";

        $include_path = realpath(get_include_path());

        $userPath = "$include_path/app/$userlevel/$path";
        $globalPath = "$include_path/app/global/$path";

        if (file_exists($userPath) && filesize($userPath) > 0) {
            return $userPath;
        }

        if (file_exists($globalPath) && filesize($globalPath) > 0) {
            return $globalPath;
        }
        return false;
    }

    public static function pathByName($name = "")
    {
        if (!$name) {
            $name = explode("?", substr($_SERVER["REQUEST_URI"], 1))[0];
            $name = preg_replace('/^controller/', '', $name);
        }

        $name = "assets" . $name;

        global $M;

        if ($M->Config->system->PRODUCTION) {
            $path = preg_replace('/^assets/', "assets/build/{$M->Config->build->TIMESTAMP}/", $name);
        } else {
            $path = preg_replace('/^assets/', 'assets/src/', $name);
        }

        $userlevel = defined('USERLEVEL') ? USERLEVEL : "global";

        $include_path = realpath(get_include_path());

        $userPath = APP_FOLDER . "$userlevel/$path";
        $globalPath = APP_FOLDER . "global/$path";

        if (file_exists($userPath) && filesize($userPath) > 0) {
            return $userPath;

        }

        if (file_exists($globalPath) && filesize($globalPath) > 0) {
            return $globalPath;
        }

        return false;
    }

    public static function content($name = "")
    {
        $path = self::pathByName($name);

        if ($path) {
            return file_get_contents($path);
        }
    }

    public static function img($name = false, $label = "", $class = "")
    {
        $widths = array(32, 64, 128, 256, 512, 1024);
        $srcset = "";

        foreach ($widths as $width) {
            $url = self::url($name, $width);
            $srcset .= "$url {$width}w, ";
        }

        $src = self::url($name);

        $srcset = rtrim($srcset, ", ");

        list($width, $height) = getimagesize(self::pathByName($name));
        $heighttag = 100 / ($width / $height);

        return <<<HTML
        <img src="$src" srcset="$srcset"
        alt="$label" title="$label"
        width="100%" height="$heighttag%"
        class="$class"
        loading="eager">
        HTML;
    }

    public static function imgSmall($name = false, $label = "", $class = "")
    {
        $widths = array(32, 64, 128, 256, 512, 1024);
        $srcset = "";

        foreach ($widths as $width) {
            $url = self::url($name, $width);
            $widthscreen = $width * 10;
            $srcset .= "$url {$widthscreen}w, ";
        }

        $src = self::url($name);

        $srcset = rtrim($srcset, ", ");

        list($width, $height) = getimagesize(self::pathByName($name));
        $heighttag = 100 / ($width / $height);

        return <<<HTML
        <img src="$src" srcset="$srcset"
        alt="$label" title="$label"
        class="$class"
        width="100%" height="$heighttag%"
        loading="lazy">
        HTML;
    }

}