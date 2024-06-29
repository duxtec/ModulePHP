<?php

namespace Resources\Config;

class Config
{
    public $database;
    public $ldap;
    public $mail;
    public $message;
    public $recaptcha;
    public $site;
    public $system;
    public $build;

    public function __construct()
    {
        $dir = dir(CONFIG_FOLDER);
        while ($arquivo = $dir->read()) {
            $ext = pathinfo($arquivo, PATHINFO_EXTENSION);
            if ($ext == "env") {
                $name = pathinfo($arquivo, PATHINFO_FILENAME);
                $this->{$name} = (object) parse_ini_file(\CONFIG_FOLDER. "$arquivo");
            }
        }

        ini_set('display_errors', 'On');
        error_reporting(E_ALL);

        if ($this->system->PRODUCTION) {
            @ini_set('zlib.output_compression', 'On');
        } else {
            @ini_set('zlib.output_compression', 'Off');
        }
    }

    public function cache()
    {
        if ($this->system->PRODUCTION) {
            header("Cache-Control: max-age=31536000");
            header("Expires: " . gmdate("D, d M Y H:i:s", time() + 31536000) . " GMT");
            header("Cache-Control: public");
        }
    }
}