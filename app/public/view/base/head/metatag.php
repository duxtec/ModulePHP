<?php

namespace ModulePHP\Base;

class Metatag
{
    public function render()
    {
        $content = <<<content
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        content;

        return $content;
    }
}