<?php

namespace Resources\Render\Html;

use Resources\Utils\Loader;

class Block
{
    public $classname;
    private $tagname;
    public $content;
    private $type;
    public $base;

    public function __construct($type = "body")
    {
        $this->type = $type;
        ob_start();
        $classname = explode('\\', get_class($this));
        $this->classname = ucfirst(end($classname));
        $this->tagname = strtolower($this->classname);

        Loader::Base("$type/$this->tagname");

        $type = ucfirst($type);

        if (class_exists("App\\View\\Base\\{$type}\\{$this->classname}")) {
            $this->base = new ("App\\View\\Base\\{$type}\\{$this->classname}");
            $this->content = $this->base->render();
        } else {
            $this->content = ob_get_contents();
        }

        ob_end_clean();
    }

    public function render()
    {
        if (isset($this->base)) {
            $this->content = $this->base->render();
        }
        if ($this->classname == "Main") {
            self::__construct();
        }
        if ($this->type == "head") {
            return <<<content
            <!-- $this->tagname -->
            $this->content
            content;
        }

        if (empty($this->content)) {
            return "";
        }

        return <<<content
        <{$this->tagname}>
            $this->content
        </{$this->tagname}>
        content;
    }
}