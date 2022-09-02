<?php

namespace ModulePHP\Base;

class Script
{
    public function render()
    {
        $content = <<<content
        <script src="/assets/js/sw-register.js" defer></script>
        content;

        return $content;
    }
}