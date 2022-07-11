<?php

namespace ModulePHP\Base;

class Style
{
    public function render()
    {
        $content = <<<content
        <link rel="stylesheet" href="assets/css/main.css">
        <link rel="stylesheet" href="assets/css/layout1.css">
        content;

        return $content;
    }
}