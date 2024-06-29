<?php

namespace Resources\Utils;

class PageResponse
{
public static function sendNotFound(string|array|Response $message = '<h1>404 - Page not found</h1>') {
        http_response_code(404);
        
        echo "$message";
    }

    public static function sendInternalServerError(string|array|Response $message = '<h1>Internal Server Error</h1>') {
        http_response_code(500);
        global $M;
        if ($M->Config->system->PRODUCTION) {
            $message = $M->Config->message->UNAVAILABLE;
        }
        echo "$message";
    }
}