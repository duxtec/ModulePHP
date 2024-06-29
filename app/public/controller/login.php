<?php

namespace App\Controller;

use Resources\Utils\Loader;
use App\Model\Login;
use Resources\Restful\BaseRestful;
use Resources\Restful\HttpResponse;

Loader::Model("login");

class LoginController extends BaseRestful
{
    public function get($data): void
    {
        if (isset($data['username']) && isset($data['password'])) {
            $username = $data['username'];
            $password = $data['password'];
            $persistent = $data['persistent'] ?? false;
            $response = Login::execute($username, $password, $persistent);
            $this->response($response);
        } else {
            HttpResponse::sendBadRequest("Username or password not provided.");
        }
    }
}

$controller = new LoginController();
$controller->handleRequest();