<?php

namespace Resources\Core;

use ErrorException;
use Error;
use Resources\Config\PreActions;
use Resources\Database\ORM;
use Resources\Manager\User;
use Resources\Render\Html\Html;
use Resources\Utils\Logger;
use Resources\Utils\Response;
use Resources\Restful\HttpResponse;
use Resources\Utils\PageResponse;

class Kernel
{
    public static function run()
    {
        global $M;

        try {
            self::initialize();
            self::executeRoute();
            self::renderResponse();
        } catch (\Throwable $th) {
            self::handleFatalError($th);
        }
    }

    private static function initialize()
    {
        global $M;

        try {
            // Load the autoload.
            require_once("resources/Config/Autoload.php");

            // Load pre actions.
            $preActions = new PreActions();
            $preActions->execute();

            $M->EntityManager = ORM::createEntityManager();
            $M->Performance->addBreakpoint("Executed Pre-actions");

            // Start Kernel execution.
            // Checks if the user is logged in and the validity of the session.
            $M->User = User::auth();
            $M->Performance->addBreakpoint("Checked session validity");

            define("USERID", (int) $M->User->id);
            define("USERLEVEL", $M->User->userlevel);
            define('USERLEVEL_FOLDER', APP_FOLDER . "{$M->User->userlevel}/");
        } catch (\Throwable $th) {
            self::logAndThrow($th, 'emergency');
        }
    }

    private static function executeRoute()
    {
        global $M;

        try {
            // Open the requested page or file
            $M->Route = new Route();
            $M->Performance->addBreakpoint("Instantiated router");

            $M->HTML = new Html();
            $M->Performance->addBreakpoint("Instantiated renderer");
        } catch (\Throwable $th) {
            self::logAndThrow($th, 'warning');
        }

        try {
            // Open the requested route.
            $M->Route->open();
            $M->Performance->addBreakpoint("Opened requested route");
        } catch (\Throwable $th) {
            self::handleRouteError($th);
        }
    }

    private static function renderResponse()
    {
        global $M;

        try {
            switch ($M->Route->type) {
                case 'page':
                    // Starts the HTML renderer.
                    echo $M->HTML->render();
                    break;

                default:
                    echo $M->Route->content;
                    break;
            }
            $M->Performance->addBreakpoint("Rendered requested route");
        } catch (\Throwable $th) {
            self::logAndThrow($th, 'warning');
        }
    }

    private static function logAndThrow(\Throwable $th, string $level)
    {
        $message = Response::ErrorThrowable($th)->getError();
        Logger::$level($message);
        throw $th;
    }

    private static function handleRouteError(\Throwable $th)
    {
        global $M;

        $message = Response::ErrorThrowable($th)->getError();
        Logger::warning($message);

        if ($M->Config->system->PRODUCTION) {
            $message = $M->Config->message->UNAVAILABLE;
        } else {
            $trace = nl2br($th->getTraceAsString());

            $message = <<<HTML
            <div>
                <h1 style='color: red'>{$th->getMessage()}</h1>
                <p>
                    {$th->getFile()}({$th->getLine()})<br>
                    $trace
                </p>
            </div>
            HTML;
        }

        if ($M->Route->type == "page") {
            ob_start();
            PageResponse::sendInternalServerError("$message");
            $M->Route->content = ob_get_clean();
        } else {
            throw $th;
        }
    }

    private static function handleFatalError(\Throwable $th)
    {
        $message = Response::ErrorThrowable($th)->getError();
        HttpResponse::sendInternalServerError($message);
    }
}

// Execute the Kernel
Kernel::run();
