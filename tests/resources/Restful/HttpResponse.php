<?php

namespace Resources\Restful;
use Resources\Utils\Response;

use Resources\Restful\ResponseCode\Informational;
use Resources\Restful\ResponseCode\Successful;
use Resources\Restful\ResponseCode\ClientError;
use Resources\Restful\ResponseCode\Redirection;
use Resources\Restful\ResponseCode\ServerError;

class HttpResponse
{
    private static function Json(string|array $data = [], bool $echo = true): string
    {
        if ($data instanceof Response) {
            if ($data->getSuccess()) {
                $data = $data->getData();
            } else {
                $data = $data->getError();
            }
        }
        try {
            global $M;
            $response = $M->Config->system->PRODUCTION?
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE):
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        } catch (\JsonException $e) {
            throw new \RuntimeException("Error encoding JSON: " . $e->getMessage());
        }

        if ($echo) {
            @header('Content-type: application/json; charset=utf-8');
            exit($response);
        }

        return $response;
    }

    private static function send (
        Informational|
        Successful|
        Redirection|
        ClientError|
        ServerError
        $response_code,
        string|array|Response $data = null) {
        $response_code = (int) $response_code->getValue();
        http_response_code($response_code);

        if ($data instanceof Response) {
            if ($data->getSuccess()) {
                $data = $data->getData();
            } else {
                $data = array('error' => $data->getError());
            }
            
        }

        if (!is_null($data)) {
            self::Json($data);
        } else {
            if (in_array($response_code, [204, 304])) {
                header('Content-Type:');
                exit;
            } else {
                throw new \Exception("Response code $response_code cannot have an empty response body.");
            }
        }
    }

     // Método para enviar resposta informativa
     private static function sendInformational(Informational $response_code, string|array|Response $data) {
        self::send($response_code, $data);
    }

    // Método para enviar resposta de sucesso
    private static function sendSuccessful(Successful $response_code, string|array|Response $data = null) {
        self::send($response_code, $data);
    }

    // Método para enviar resposta de redirecionamento
    private static function sendRedirection(Redirection $response_code, string|array|Response $data = null) {
        self::send($response_code, $data);
    }

    // Método para enviar resposta de erro do cliente
    private static function sendClientError(ClientError $response_code, string|array|Response $data = null) {
        self::sendError($response_code, $data);
    }

    // Método para enviar resposta de erro do servidor
    private static function sendServerError(ServerError $response_code, string|array|Response $data = null) {
        self::sendError($response_code, $data);
    }

    private static function sendError (
        ClientError|ServerError $response_code,
        string|array|Response $data
        ) {

        if ($data instanceof Response) {
            $data = $data->getError();
        }

        $data = array('error' => $data);

        self::send($response_code, $data);
    }

    public static function sendOK($data)
    {
        self::sendSuccessful(Successful::OK, $data);
    }

    public static function sendCreated($data)
    {
        self::sendSuccessful(Successful::CREATED, $data);
    }

    public static function sendNoContent()
    {
        self::sendSuccessful(Successful::NO_CONTENT);
    }

    public static function sendBadRequest(string|array|Response $message = 'Bad Request')
    {
        self::sendClientError(ClientError::BAD_REQUEST, $message);
    }

    public static function sendUnauthorized(string|array|Response $message = 'Unauthorized')
    {
        self::sendClientError(ClientError::UNAUTHORIZED, $message);
    }

    public static function sendForbidden(string|array|Response $message = 'Forbidden')
    {
        self::sendClientError(ClientError::FORBIDDEN, $message);
    }

    public static function sendNotFound(string|array|Response $message = 'Not Found')
    {
        self::sendClientError(ClientError::NOT_FOUND, $message);
    }

    public static function sendMethodNotAllowed(string|array|Response $message = 'Method Not Allowed')
    {
        self::sendClientError(ClientError::METHOD_NOT_ALLOWED, $message);
    }

    public static function sendInternalServerError(string|array|Response $message = 'Internal Server Error')
    {
        global $M;
        if ($M->Config->system->PRODUCTION) {
            $message = $M->Config->message->UNAVAILABLE;
        }
        self::sendServerError(ServerError::INTERNAL_SERVER_ERROR, $message);
    }
}