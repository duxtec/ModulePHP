<?php

namespace Resources\Render\Html;

use Resources\Render\Html\Body\Header;
use Resources\Render\Html\Body\Aside;
use Resources\Render\Html\Body\Main;
use Resources\Render\Html\Body\Footer;

class Body
{
    public $header;
    public $aside;
    public $main;
    public $footer;

    public function __construct()
    {
        $this->header = new Header();
        $this->aside = new Aside();
        $this->footer = new Footer();
        $this->main = new Main();
    }

    public function render()
    {
        $header = $this->header->render();
        $aside = $this->aside->render();
        $footer = $this->footer->render();
        $main = $this->main->render();

        $html = <<<html
            <body>
                {$header}
                {$aside}
                {$main}
                {$footer}
            </body>
        html;

        return $html;
    }
}