<?php

namespace App\Controller;

use Resources\Utils\Loader;
use Resources\Restful\BaseRestful;
use App\Model\User;

Loader::Model("user");

class UserController extends BaseRestful
{
    public function get($data): void
    {
        $response = User::read($data);
        $this->response($response);
    }

    public function post($data): void
    {
        $response = User::create($data);
        $this->response($response);
    }

    public function put($data): void
    {
        $response = User::update($data);
        $this->response($response);
    }

    public function patch($data): void
    {
        $response = User::update($data);
        $this->response($response);
    }

    public function delete($data): void
    {
        $response = User::delete($data);
        $this->response($response);
    }
}

$controller = new UserController();
$controller->handleRequest();