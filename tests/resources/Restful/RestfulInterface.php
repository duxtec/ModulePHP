<?php
namespace Resources\Restful;

interface RestfulInterface
{
    public function get(array $params);
    public function post(array $params);
    public function put(array $params);
    public function delete(array $params);
    public function patch(array $params);
}