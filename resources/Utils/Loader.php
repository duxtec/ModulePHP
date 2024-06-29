<?php

namespace Resources\Utils;

use Resources\Manager\User;
use Resources\Restful\HttpResponse;

class Loader
{
    public static function Model($name = "")
    {
        return self::APP($name, "model");
    }

    public static function View($name = "")
    {
        return self::APP($name, "view");
    }

    public static function Controller($name = "")
    {
        global $M;
        $message = $M->Config->message;

        $controller = self::APP($name, "controller");

        if (!$controller) {
            HttpResponse::sendNotFound();
        }

        return $controller;
    }

    public static function Resource($name = "kernel")
    {
        return require_once ("resources/$name.php");
    }

    public static function Plugin($name = "")
    {
        return require_once ("plugins/$name.php");
    }

    public static function Page($name = "")
    {
        global $M;
        $message = $M->Config->message;

        if ($name == "logout") {
            User::logout();
        }

        $page = self::APP($name, "view/pages");

        if (!$page) {
            PageResponse::sendNotFound("");
            $page = self::APP("404", "view/pages");

            if (!$page) {
                $page = "<h1>$message->PAGE_NOT_FOUND</h1>";
                PageResponse::sendNotFound("$page");
            }        
        }

        return $page;
    }
    public static function Module($name = "")
    {
        return self::APP($name, "view/modules");
    }

    public static function Base($name = "")
    {
        return self::APP($name, "view/base");
    }

    public static function Assets($name = "")
    {
        global $M;
        if ($M->Config->system->PRODUCTION) {
            // Retorna o caminho do arquivo com o timestamp no diretório de construção
            return self::APP($name, "assets/build", "");
        } else {
            return self::APP($name, "assets/src", "");
        }
    }

    public static function path()
    {
        $path = explode("?", substr($_SERVER["REQUEST_URI"], 1))[0];
        $path = preg_replace('/^controller/', '', $path);
        $path = preg_replace('/^assets/', '', $path);

        return $path;
    }

    public static function APP($name, $path, $ext = "php")
    {
        if (!$name) {
            $name = self::Path();
        }

        if ($ext) {
            $ext = ".$ext";
        }

        $userlevel = USERLEVEL;

        $include_path = realpath(get_include_path());

        $userPath = APP_FOLDER . "$userlevel/$path/$name$ext";
        $globalPath = APP_FOLDER . "global/$path/$name$ext";

        if (file_exists($userPath) && filesize($userPath) > 0) {
            return require_once ($userPath);
        }

        if (file_exists($globalPath) && filesize($globalPath) > 0) {
            return require_once ($globalPath);
        }

        return "";
    }
}