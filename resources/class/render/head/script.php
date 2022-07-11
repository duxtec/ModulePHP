<?php

namespace ModulePHP;

class Script
{
    private $content = [];
    public $base;

    function __construct()
    {
        ob_start();

        MVC::Base("head/script");

        $this->pushContent(ob_get_contents());
        ob_end_clean();

        if (class_exists('ModulePHP\Base\Script')) {
            $this->base = new Base\Script;
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

        $html = "<!-- Scripts -->";
        foreach ($this->content as $content) {
            if (is_string($content)) {
                $html .= $content;
            }
        }

        return $html;
    }
}