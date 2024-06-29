<?php

namespace Resources\Config;

use Resources\Audit\Performance;

define('ROOT_FOLDER', dirname(dirname(dirname(__FILE__)))."/");
define('APP_FOLDER', ROOT_FOLDER . "app/");
define('CONFIG_FOLDER', ROOT_FOLDER . "config/");

//Sets the default include path.
set_include_path(ROOT_FOLDER);

//Load composer dependencies.
require_once ("vendor/autoload.php");

//Create the Module Object
$M = new \stdClass();
$MPHP = &$M;
$ModulePHP = &$M;

$M->Performance = new Performance();

$M->Config = new Config();

$M->Performance->addBreakpoint("Loaded settings");

try {
    //Loads system resources.

    $M->Performance->addBreakpoint("Loaded resources");
} catch (\Throwable $th) {
    throw $th;
}