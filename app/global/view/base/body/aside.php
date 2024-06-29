<?php

namespace App\View\Base\Body;

class Aside
{
    public $links;

    public function __construct()
    {
        $this->links = [];
    }

    public function render()
    {
        $links = "";

        foreach ($this->links as $link) {
            $links .= <<<html
            <a href="{$link['href']}" target="_blank">
                <i class="fa-solid fa-{$link['icon']}"></i>
                {$link['name']}
            </a>
            html;
        }

        $html = <<<html

        html;

        return $html;
    }
}