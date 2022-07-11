<?php

namespace ModulePHP;

class MVC
{

    static function Resource($name = "kernel")
    {
        return require_once("resources/class/$name.php");
    }

    static function Plugin($name = "")
    {
        if (!$name) {
            $name = explode("?", substr($_SERVER["REQUEST_URI"], 1))[0];
            $name = preg_replace('/^controller/', '', $name);
        }
        return require_once("plugins/$name.php");
    }

    static function Model($name = "")
    {
        if (!$name) {
            $name = explode("?", substr($_SERVER["REQUEST_URI"], 1))[0];
            $name = preg_replace('/^controller/', '', $name);
        }
        $userlevel = USERLEVEL;
        return require_once("app/$userlevel/model/$name.php");
    }

    static function Module($name = "")
    {
        if (!$name) {
            $name = explode("?", substr($_SERVER["REQUEST_URI"], 1))[0];
            $name = preg_replace('/^controller/', '', $name);
        }
        $userlevel = USERLEVEL;
        return require_once("app/$userlevel/view/modules/$name.php");
    }

    static function Base($name = "")
    {
        if (!$name) {
            $name = explode("?", substr($_SERVER["REQUEST_URI"], 1))[0];
            $name = preg_replace('/^controller/', '', $name);
        }
        $userlevel = USERLEVEL;
        return require_once("app/$userlevel/view/base/$name.php");
    }

    static function JsonResponse($content = "")
    {
        echo json_encode($content, JSON_UNESCAPED_UNICODE);
    }
}