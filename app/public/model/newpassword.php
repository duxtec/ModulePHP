<?php

use ModulePHP\User;

class RecoveryPassword
{
    public $userid;
    public $recoverycode;
    public $password;

    public function execute()
    {
        $login = User::newPassword(
            $this->userid,
            $this->recoverycode,
            $this->password
        );
        return true;
    }
}