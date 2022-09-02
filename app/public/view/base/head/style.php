<?php

namespace ModulePHP\Base;

class Style
{
    public function render()
    {
        $content = <<<content
        <link rel="preload" href="assets/css/layout1.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="assets/css/layout1.css"></noscript>
        content;

        return $content;
    }
}