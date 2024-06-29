<?php

use ModulePHP\User;

class RecoveryPassword
{
    public $username;
    public $email;
    public $message;

    public function execute()
    {
        $login = User::recoveryPassword(
            $this->username,
            $this->email,
            $this->message
        );
        return true;
    }
}