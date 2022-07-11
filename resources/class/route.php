<?php

namespace ModulePHP;

class Route
{
    public $path;
    public $lite = false;
    public $content;
    public $type;
    public $userlevel;

    function __construct()
    {

        $this->path = isset($_SERVER["REQUEST_URI"]) ? explode("?", substr($_SERVER["REQUEST_URI"], 1))[0] : "";
        $this->userlevel = USERLEVEL;
        $this->typeconfig();
    }

    function typeconfig()
    {
        $path_array = explode("/", $this->path);
        switch ($path_array[0]) {
            case 'controller':
                header('Content-type: application/json; charset=utf-8');
                array_shift($path_array);
                $this->path = implode("/", $path_array);
                $this->type =  "controller";
                $this->path =  "app/$this->userlevel/controller/$this->path.php";
                break;

            case 'assets':
                switch ($path_array[1]) {
                    case 'css':
                        header("Content-type: text/css");
                        break;

                    case 'js':
                        header("Content-type: application/javascript");
                        break;

                    default:
                        header("Content-type:" . @mime_content_type("../public_html/$this->path"));
                        break;
                }
                $this->type =  "assets";
                $this->path =  "public_html/$this->path";
                break;

            default:
                $this->type =  "page";
                header("Content-type: text/html; charset=utf-8");
                if (!$this->path) {
                    $this->path = "index";
                }

                $this->path =  "app/$this->userlevel/view/pages/$this->path.php";
                break;
        }
    }

    function lite()
    {
        $path_array = explode("/", $this->path);
        if (array_reverse($path_array)[0] == "lite") {
            array_pop($path_array);
            $this->lite = true;
        } else {
            $this->lite = false;
        }
        $this->path = implode("/", $path_array);
    }

    function liteaction()
    {
    }

    function open()
    {
        global $M;
        if (!$result = is_file($this->path)) {
            if ($this->type == "page") {
                if (!@$result = stream_resolve_include_path($this->path)) {
                    $this->path = "app/$this->userlevel/view/pages/404.php";
                    if (!@$result = stream_resolve_include_path($this->path)) {
                        $this->path = "app/unlogged/view/pages/404.php";
                        if (!@$result = stream_resolve_include_path($this->path)) {
                            $this->content = "<h1>Página Inexistente!</h1>";
                            return $this->content;
                        }
                    }
                }
            } else {
                $this->content = array(
                    'success' => false,
                    'status' => 'Arquivo inexistente ou login expirado, tente efetuar login novamente!'
                );
                return MVC::JsonResponse($this->content);
            }
        }

        ob_start();
        require_once($this->path);
        $this->content = ob_get_contents();
        ob_end_clean();
        return $this->content;
    }
}