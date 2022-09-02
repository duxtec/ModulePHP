<?php

namespace ModulePHP\Database;

use mysqli;

class Con extends mysqli
{
    private static $host;
    private static $user;
    private static $pw;
    private static $name;
    private static $port;
    function __construct()
    {
        global $M;
        self::$host = $M["Config"]["DB_HOST"];
        self::$user = $M["Config"]["DB_USER"];
        self::$pw = $M["Config"]["DB_PW"];
        self::$name = $M["Config"]["DB_NAME"];
        self::$port = $M["Config"]["DB_PORT"];
        if (
            self::$host  &&
            self::$user &&
            self::$pw &&
            self::$name

        ) {
            parent::__construct(
                self::$host,
                self::$user,
                self::$pw,
                self::$name,
                self::$port
            );
            if ($this->connect_error) {
                $this->status = "Database connection error.";
            } else {
                $this->set_charset("utf8");
            }
        } else {
            $this->status = "Database not defined.";
        }
    }
}

class DB
{
    public $status;

    static function escape($string)
    {
        $con = new Con();
        $string = $string === true ? "True" : ($string === false ? "False" : $string);
        $string = $con->real_escape_string($string);
        $con->close();
        return $string;
    }

    static function query($query)
    {
        $con = new Con();
        $return = $con->query($query);
        $con->close();
        return $return;
    }
}