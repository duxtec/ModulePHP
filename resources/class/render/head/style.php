<?php

namespace ModulePHP;

class Style
{
    private $content = [];
    public $base;

    function __construct()
    {
        ob_start();

        MVC::Base("head/style");

        $this->pushContent(ob_get_contents());
        ob_end_clean();

        if (class_exists('ModulePHP\Base\Style')) {
            $this->base = new Base\Style;
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

        $html = "<!-- Estilos -->";
        foreach ($this->content as $content) {

            if (is_string($content)) {
                $html .= $content;
            }
        }

        return $html;
    }
}