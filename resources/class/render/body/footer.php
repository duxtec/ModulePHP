<?php

namespace ModulePHP;

class Footer
{
    private $content = [];
    public $base;

    function __construct()
    {
        ob_start();

        MVC::Base("body/footer");

        $this->pushContent(ob_get_contents());
        ob_end_clean();

        if (class_exists('ModulePHP\Base\Footer')) {
            $this->base = new Base\Footer;
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

        $html = "<footer>";
        foreach ($this->content as $content) {
            if (is_string($content)) {
                $html .= $content;
            }
        }

        $html .= "</footer>";

        return $html;
    }
}