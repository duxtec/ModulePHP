<?php

namespace ModulePHP;

use ErrorException;
use Error;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

//Sets the default include path.
set_include_path("../");

//Load composer dependencies.
require_once("vendor/autoload.php");

//Load the MVC architecture class.
require_once("resources/class/mvc.php");

//Load system settings.
require_once("config/global.php");

#Turning errors into exceptions.
if (DEBUG) {
    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
        if (0 === error_reporting()) {
            return false;
        }

        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    });
}

try {
    //Load website settings.
    require_once("config/website.php");

    //Loads system resources.
    MVC::Resource("database/db");
    MVC::Resource("database/select");
    MVC::Resource("database/insert");
    MVC::Resource("database/update");
    MVC::Resource("database/delete");
    MVC::Resource("ldap");
    MVC::Resource("user");
    MVC::Resource("route");
    MVC::Resource("render/html");

    //Starts Kernel execution.
    //Checks if the user is logged in and the validity of the session.
    $M["User"] = User::auth();

    //Stores the user id and level.
    define("USERID", $M["User"]->id);
    define("USERLEVEL", $M["User"]->userlevel);

    //Start the router
    $M["Route"] = new Route();

    //Starts the HTML renderer.
    $M["Render"] = new Html();

    //Opens the requested route.
    $M["Route"]->open();

    define("CONTENT", $M["Route"]->content);


    //Abre a página ou arquivo requisitado.
    try {
        switch ($M["Route"]->type) {
            case 'page':

                $M["Render"]->html($M["Route"]->content);
                break;

            default:
                echo $M["Route"]->content;
                break;
        }
    } catch (ErrorException $e) {
        $log = new Logger("Kernel");
        $datetime = date("Y-m-d");
        $log->pushHandler(new StreamHandler("../logs/$datetime.log", Logger::WARNING));
        $log->warning("{$e->getMessage()} IN {$e->getFile()} IN line {$e->getLine()}");

        echo "<h1>Sistema indisponível<h1>";
    }
} catch (ErrorException $e) {
    $log = new Logger("Kernel");
    $datetime = date("Y-m-d");
    $log->pushHandler(new StreamHandler("../logs/$datetime.log", Logger::WARNING));
    $log->warning("{$e->getMessage()} IN {$e->getFile()} IN line {$e->getLine()}");

    echo "<h1>Sistema indisponível<h1>";
} catch (Error $e) {
    $log = new Logger("Kernel");
    $datetime = date("Y-m-d");
    $log->pushHandler(new StreamHandler("../logs/$datetime.log", Logger::EMERGENCY));
    $log->emergency("{$e->getMessage()} IN {$e->getFile()} IN line {$e->getLine()}");

    echo "<h1>Sistema indisponível<h1>";
}