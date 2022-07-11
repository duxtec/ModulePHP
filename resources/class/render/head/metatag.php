<?php

namespace ModulePHP;

class Metatag
{
    private $content = [];
    public $base;

    function __construct()
    {
        ob_start();

        MVC::Base("head/metatag");

        $this->pushContent(ob_get_contents());
        ob_end_clean();

        if (class_exists('ModulePHP\Base\Metatag')) {
            $this->base = new Base\Metatag;
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

        $html = "<!-- Metatags -->";
        foreach ($this->content as $content) {
            if (is_string($content)) {
                $html .= $content;
            }
        }
        if ($this->title) {
            $site_name = SITE_NAME;
            $html .= "<title>$this->title - {$site_name}</title>";
        }

        return $html;
    }
}