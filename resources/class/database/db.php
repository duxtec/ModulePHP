<?php
namespace ModulePHP\Database;
use mysqli;

class Con extends mysqli{
    function __construct() {
        if(db_host && db_user && db_pw && db_name){
            parent::__construct(db_host, db_user, db_pw, db_name);
            if ($this->connect_error) {
                $this->status = [0, "Erro de conexão com o banco de dados, contate o suporte."];
            } else {
                $this->set_charset("utf8");
            }
        } else {
            $this->status = [0, "Banco de dados não definido"];
        }
    }
}

class DB {
    public $status;

    static function escape($string) {
        $con = New Con();
        $string = $string === true ? "True" : ($string === false ? "False" : $string);
        $string = $con->real_escape_string($string);
        $con->close();
        return $string;
    }

    static function query($query) {
        $con = New Con();
        $return = $con->query($query);
        $con->close();
        return $return;
    }
}