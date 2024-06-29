<?php

namespace Resources\Core;

use Resources\Utils\Loader;
use Resources\Utils\Asset;

class Route
{
    public $path;
    public $lite = false;
    public $content;
    public $type;
    public $userlevel;

    public function __construct()
    {

        $this->path = isset($_SERVER["REQUEST_URI"]) ? explode("?", substr($_SERVER["REQUEST_URI"], 1))[0] : "";
        $this->userlevel = USERLEVEL;
        $this->typeconfig();
    }

    public function typeconfig()
    {
        $path_array = explode("/", $this->path);
        switch ($path_array[0]) {
            case 'controller':
                @header('Content-type: application/json; charset=utf-8');
                array_shift($path_array);
                $this->path = implode("/", $path_array);
                $this->type = "controller";
                $this->path = "$this->path";
                break;

            case 'assets':
                $extension = pathinfo($this->path, PATHINFO_EXTENSION);
                switch ($extension) {
                    case 'css':
                    case 'scss':
                    case 'sass':
                    case 'less':
                    case 'styl':
                        header("Content-type: text/css");
                        break;

                    case 'js':
                    case 'mjs':
                    case 'jsx':
                    case 'ts':
                    case 'coffee':
                        header("Content-type: application/javascript");
                        break;

                    default:
                        $assetpath = Asset::path($this->path);
                        if ($assetpath) {
                            @header("Content-type:" . @mime_content_type(Asset::path($this->path)));
                        }

                        break;
                }
                $this->type = "assets";
                array_shift($path_array);
                $this->path = implode("/", $path_array);

                break;

            default:
                $this->type = "page";
                @header("Content-type: text/html; charset=utf-8");
                if (!$this->path) {
                    $this->path = "index";
                }

                break;
        }
    }

    public function lite()
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

    public function liteaction()
    {
    }

    public function open()
    {
        global $M;
        ob_start();

        try {
            switch ($this->type) {
                case 'controller':
                    Loader::Controller($this->path);
                    break;

                case 'assets':
                    Loader::Assets($this->path);
                    break;

                default:
                    Loader::Page($this->path);
                    break;
            }

            // Captura o conteúdo do buffer
            $this->content = ob_get_clean();

        } catch (\Throwable $th) {
            if ($M->Config->system->PRODUCTION && $this->type == "page") {
                $this->content = $M->Config->message->UNAVAILABLE;
            } else {
                throw $th;
            }
            switch ($this->type) {
                case 'controller':
                    Loader::Controller($this->path);
                    break;

                case 'assets':
                    Loader::Assets($this->path);
                    break;

                default:
                    Loader::Page($this->path);
                    break;
            }
            ob_end_clean();
            throw $th;
        }
    }

}