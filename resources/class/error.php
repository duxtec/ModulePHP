<?php

namespace ModulePHP;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Error
{

    static function emergency($message = UNAVAILABLE_MESSAGE)
    {
        Error::action($message, Logger::EMERGENCY);
    }

    static function warning($message = UNAVAILABLE_MESSAGE)
    {
        Error::action($message, Logger::WARNING);
    }

    static function action($message = UNAVAILABLE_MESSAGE, $level = Logger::DEBUG)
    {
        $log = new Logger("Kernel");
        $datetime = date("Y-m-d");
        $log->pushHandler(new StreamHandler("../logs/$datetime.log", $level));
        $log->warning($message);

        if (!DEBUG) {
            $message = UNAVAILABLE_MESSAGE;
        }

        global $M;

        if (isset($M["Route"]->type)) {
            switch ($M["Route"]->type) {
                case 'page':
                    echo "<h1>$message<h1>";
                    break;
                default:
                    $message = array(
                        'success' => false,
                        'status' => $message
                    );
                    MVC::JsonResponse($message);
            }
        } else {
            echo "<h1>$message<h1>";
        }
    }
}