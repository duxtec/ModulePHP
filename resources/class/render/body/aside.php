<?php

namespace ModulePHP;

class Aside
{
    private $content = [];
    public $base;

    function __construct()
    {
        ob_start();

        MVC::Base("body/aside");

        $this->pushContent(ob_get_contents());
        ob_end_clean();

        if (class_exists('ModulePHP\Base\Aside')) {
            $this->base = new Base\Aside;
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

        $html = "<aside>";
        foreach ($this->content as $content) {
            if (is_string($content)) {
                $html .= $content;
            }
        }

        $html .= "</aside>";

        return $html;
    }
}