<?php

namespace App\Model;
use Resources\Manager\User;

class Login
{
    public static function execute($username, $password, $persistent)
    {
        return User::login($username, $password, $persistent);
    }
}