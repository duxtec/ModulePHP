<?php
namespace Resources\Restful;

use Resources\Utils\Response;
use Resources\Restful\HttpResponse;

class BaseRestful implements RestfulInterface
{
    protected $MethodNotAllowedMessage;

    public function __construct($MethodNotAllowedMessage = "Method not allowed")
    {
        $this->MethodNotAllowedMessage = $MethodNotAllowedMessage;
    }

    public function get(array $data): void
    {
        HttpResponse::sendMethodNotAllowed($this->MethodNotAllowedMessage);
    }

    public function post(array $data): void
    {
        HttpResponse::sendMethodNotAllowed($this->MethodNotAllowedMessage);
    }

    public function put(array $data): void
    {
        HttpResponse::sendMethodNotAllowed($this->MethodNotAllowedMessage);
    }

    public function delete(array $data): void
    {
        HttpResponse::sendMethodNotAllowed($this->MethodNotAllowedMessage);
    }
    
    public function patch(array $data): void
    {
        HttpResponse::sendMethodNotAllowed($this->MethodNotAllowedMessage);
    }

    public function response(Response $response): void
    {
        if ($response->getSuccess()) {
            $data = $response->getData();
            if (!empty($data)) {
                if ($_SERVER['REQUEST_METHOD'] == "POST") {
                    HttpResponse::sendCreated($data);
                }
                HttpResponse::sendOK($data);
            }
            HttpResponse::sendNoContent();
        }
        HttpResponse::sendBadRequest($response->getError());
    }

    public function handleRequest(): void
    {
        $request_method = strtolower($_SERVER['REQUEST_METHOD']);

        switch ($request_method) {
            case 'get':
                $this->get($_REQUEST);
                break;
            case 'post':
                $this->post($_REQUEST);
                break;
            case 'put':
                parse_str(file_get_contents("php://input"), $put_params);
                $this->put($put_params);
                break;
            case 'patch':
                parse_str(file_get_contents("php://input"), $patch_params);
                $this->patch($patch_params);
                break;
            case 'delete':
                $this->delete($_REQUEST);
                break;
            default:
                HttpResponse::sendMethodNotAllowed("Method not allowed");
        }
    }
}