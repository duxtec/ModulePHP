<?php

namespace App\View\Base\Head;

class Script
{
    public function render()
    {
        $content = <<<HTML
        <script src=" assets/js/disableforms.js" defer></script>
        HTML;

        return $content;
    }
}