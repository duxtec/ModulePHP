<?php

namespace ModulePHP;

class Header
{
    private $content = [];
    public $base;

    function __construct()
    {
        ob_start();

        MVC::Base("body/header");

        $this->pushContent(ob_get_contents());
        ob_end_clean();

        if (class_exists('ModulePHP\Base\Header')) {
            $this->base = new Base\Header;
        }
    }

    function pushContent($content)
    {
        array_push($this->content, $content);
    }

    function html()
    {
        if ($this->base) {
            $this->pushContent($this->base->render());
        }

        $html = "<header>";
        foreach ($this->content as $content) {
            if (is_string($content)) {
                $html .= $content;
            }
        }

        $html .= "</header>";

        return $html;
    }
}