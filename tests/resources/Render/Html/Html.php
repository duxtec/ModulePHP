<?php

namespace Resources\Render\Html;

use Resources\Render\Html\Body;
use Resources\Render\Html\Head;

class Html
{
    public $head;
    public $body;
    public $title;
    public $config;

    public function __construct()
    {
        global $M;
        $this->config = $M->Config->site;

        //Importing head modules
        $this->head = new Head();

        //Importing body modules
        $this->body = new Body();
    }

    public function render()
    {
        global $M;

        $head = $this->head->render();
        $body = $this->body->render();

        $html = <<<html
        <!DOCTYPE html>
        <html lang="{$this->config->LANG}" dir="ltr">
            $head
            $body
        </html>
        html;

        return $html;
    }
}