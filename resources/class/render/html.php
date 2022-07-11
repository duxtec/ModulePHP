<?php

namespace ModulePHP;

MVC::Resource("render/head/style");
MVC::Resource("render/head/script");
MVC::Resource("render/head/metatag");
MVC::Resource("render/head/favicon");
MVC::Resource("render/head/other");
MVC::Resource("render/body/header");
MVC::Resource("render/body/aside");
MVC::Resource("render/body/main");
MVC::Resource("render/body/footer");

class Html
{
    public $head;
    public $body;
    public $title;

    public function __construct()
    {
        //Importing head modules
        $this->head = new Head();

        //Importing body modules
        $this->body = new Body();
    }

    public function html($content)
    {
        if ($this->title) {
            $this->head->metatag->title = $this->title;
        }

        $head = $this->head->html();
        $body = $this->body->html($content);
        $html = <<<html
        <!DOCTYPE html>
        <html lang="pt-br" dir="ltr">
            $head
            $body
        </html>
        html;

        echo $html;
    }
}

class Head
{
    public $style;
    public $script;
    public $metatag;
    public $favicon;
    public $other;

    public function __construct()
    {
        $this->style = new Style();
        $this->script = new Script();
        $this->metatag = new Metatag();
        $this->favicon = new Favicon();
        $this->other = new Other();
    }

    public function html()
    {
        $html = <<<html
        <head>
            {$this->style->html()}
            {$this->script->html()}
            {$this->metatag->html()}
            {$this->favicon->html()}
            {$this->other->html()}
        </head>
        html;

        return $html;
    }
}

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
        $this->main = new Main();
        $this->footer = new Footer();
    }

    public function html($content)
    {
        $html = <<<html
        <body>
            {$this->header->html()}
            {$this->aside->html()}
            {$this->main->html($content)}
            {$this->footer->html()}
        </body>
        html;

        return $html;
    }
}