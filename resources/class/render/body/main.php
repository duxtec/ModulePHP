<?php

namespace ModulePHP;

class Main
{
    private $content = [];
    public $base;

    function __construct()
    {
    }

    function pushContent($content)
    {
        array_push($this->content, $content);
    }

    function html($content)
    {
        ob_start();

        MVC::Base("body/main");

        if (class_exists('ModulePHP\Base\Main')) {
            $this->base = new Base\Main;
            $content = $this->base->render();
        } else {
            $content_main = ob_get_contents();
        }

        ob_end_clean();

        if (!empty($content_main)) {
            $content = $content_main;
        } else {
            $content = CONTENT;
        }

        array_unshift($this->content, $content);

        $html = "<main>";
        foreach ($this->content as $content) {
            if (is_string($content)) {
                $html .= $content;
            }
        }

        $html .= "</main>";

        return $html;
    }
}