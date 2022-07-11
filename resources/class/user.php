<?php
namespace ModulePHP;
use ModulePHP\Database\DB AS DB;
use ModulePHP\Database\Select AS Select;
use ModulePHP\Database\Insert AS Insert;

use Exception;

class User {
    public $id;
    public $userlevel;
    public $ip;

    function __construct() {
        
    }
    
    static function login($username){
        $username = DB::escape($username);
        $sessionid = DB::escape(session_id());
        $ip = DB::escape(User::capture_userip());

        if (!$username || !$sessionid || !$ip) {
            throw new Exception("Erro ao construir sessão ou identificar IP!");
            return false;
        }

        $select = new Select;
        $select->table = "Users";
        $select->column("id");

        $select->where("username", $username);
        $select->limit = 1;

        $id = $select->result()[0]["id"];
        
        if(!$id){
            throw new Exception("Usuário inexistente!");
            return False;
        }

        $insert = new Insert("Sessions");
        $insert->column([
            "UserID",
            "PHPsession_id",
            "UserIP"
        ]);

        $insert->value([
            $id, $sessionid, $ip
        ]);

        if (!$insert->execute()) {
            throw new Exception("Erro ao efetuar login!");
        }

        $_SESSION["userID"] = $id;
        return True;
    }

    static function auth(){
        if (isset($_SESSION["userID"])) {
            $id = DB::escape($_SESSION["userID"]);
            $sessionid = DB::escape(session_id());
            $ip = DB::escape(User::capture_userip());

            if ($id && $sessionid && $ip) {

                $select = new Select("Userlevel");
                $select->column("Userlevel.name", "userlevel");
                $select->join("Users", "INNER", "Userlevel.id", "Users.userlevel_id");
                $select->join("Sessions", "INNER", "Users.id", "Sessions.UserID");
                $select->where([
                    ["Sessions.UserID", $id],
                    ["Sessions.PHPsession_id", $sessionid],
                    ["Sessions.UserIP", $ip]
                ]);
                
                if ($result = $select->result()) {
                    return (object) array(
                        "id"=> $id,
                        "userlevel"=> $result[0]["userlevel"]
                    );
                }
            }
        }
        return (object) array(
            "id"=> 0,
            "userlevel"=> "public"
        );
    }

    static function capture_userip() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] AS $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
        return $ip;
    }

    
}