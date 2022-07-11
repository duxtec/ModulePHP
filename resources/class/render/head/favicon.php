<?php

namespace ModulePHP;

class Favicon
{
    private $content = [];
    public $base;

    function __construct()
    {
        ob_start();

        MVC::Base("head/favicon");

        $this->pushContent(ob_get_contents());
        ob_end_clean();

        if (class_exists('ModulePHP\Base\Favicon')) {
            $this->base = new Base\Favicon;
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

        $html = "<!-- Favicons -->";
        foreach ($this->content as $content) {
            if (is_string($content)) {
                $html .= $content;
            }
        }

        return $html;
    }
}
