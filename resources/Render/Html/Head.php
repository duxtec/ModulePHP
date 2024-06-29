<?php

namespace Resources\Render\Html;

use Resources\Render\Html\Head\Style;
use Resources\Render\Html\Head\Script;
use Resources\Render\Html\Head\Metatag;
use Resources\Render\Html\Head\Favicon;
use Resources\Render\Html\Head\Other;

class Head
{
    public $style;
    public $script;
    public $metatag;
    public $favicon;
    public $other;
    public $pwa = "";

    public function __construct()
    {
        global $M;

        $this->style = new Style();
        $this->script = new Script();
        $this->metatag = new Metatag();
        $this->favicon = new Favicon();
        $this->other = new Other();

        if ($M->Config->system->PWA) {
            $this->pwa = <<<content
            <link rel="manifest" href="/manifest">
            <script>
                /* Only register a service worker if it's supported */
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('/sw');
                }
            </script>
            content;
        }
    }

    public function render(): string
    {

        $html = <<<html
        <head>
            {$this->style->render()}
            {$this->script->render()}
            {$this->metatag->render()}
            {$this->favicon->render()}
            {$this->other->render()}
            {$this->pwa}
        </head>
        html;

        return $html;
    }
}