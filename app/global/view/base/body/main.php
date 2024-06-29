<?php

namespace App\View\Base\Body;

class Main
{
    public function render()
    {
        global $M;
        return $M->Route->content;
    }
}