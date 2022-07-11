<?php

namespace ModulePHP;

class Other
{
    private $content = [];
    public $base;

    function __construct()
    {
        ob_start();

        MVC::Base("head/other");

        $this->pushContent(ob_get_contents());
        ob_end_clean();

        if (class_exists('ModulePHP\Base\Other')) {
            $this->base = new Base\Other;
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

        $html = "<!-- Outras tags do head -->";
        foreach ($this->content as $content) {
            if (is_string($content)) {
                $html .= $content;
            }
        }

        return $html;
    }
}