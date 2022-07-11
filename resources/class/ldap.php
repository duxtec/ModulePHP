<?php
namespace ModulePHP;
use Exception;

class LDAP {
    private $con;

    function __construct() {
        if(ldap_server && ldap_domain && ldap_porta){
            $this->con = ldap_connect(ldap_server, ldap_porta);
            if ($this->con) {
                return true;
            } else {
                throw new Exception("Erro no login do LDAP");
                return false;
            }
        } else {
            throw new Exception("LDAP não configurado");
            return false;
        }
    }

    function bind(){
        if (ldap_bind($this->con, ldap_user, ldap_pw)) {
            return true;
        } else {
            throw new Exception("Erro no login do LDAP");
            return false;
        }
    }

    function addUser($user, $gn, $sn, $o, $m, $p){
        
        $info["cn"] = "$gn $sn";
        $info["givenName"] = "$gn";
        $info["sn"] = "$sn";
        $info["displayName"] = "$gn $sn";
        $info["company"] = "NIDF";
        $info["name"] = "$gn $sn";
        $info["userPrincipalName"] = "$user@localhost";
        $info["mail"] = "$user@localhost";
        $info["objectCategory"] = "CN=Person,CN=Schema,CN=Configuration,DC=domain,DC=com";
        $info["uid"] = "$user";
        $info["sAMAccountName"] = "$user";
        $info["userPassword"] = "$p";
        $info["objectclass"][0] = "top";
        $info["objectclass"][1] = "posixAccount";
        $info["objectclass"][2] = "person";
        $info["objectclass"][3] = "organizationalPerson";
        $info["objectclass"][4] = "user";

        $dn = "CN=$gn $sn,OU=Users,OU=Organization,DC=domain,DC=com";

        if ($result = ldap_add_ext($this->con, $dn, $info)) {
            $errcode = $errmsg = $refs =  null;
            if (ldap_parse_result($this->con, $result, $errcode, $dn, $errmsg, $refs)) {
                $this->status["errocode"] = $errcode; 
                $this->status["dn"] = $dn; 
                $this->status["errmsg"] = $errmsg; 
                $this->status["refs"] = $refs; 
                // do something with $errcode, $dn, $errmsg and $refs
            }

            return true;
        } else {
            throw new Exception("Erro ao criar novo usuário!");
            return false;
        }
    }

    function passwordVerify($user, $pw){
        if(filter_var($user, FILTER_VALIDATE_EMAIL)){
            $user = $user;
        } else {
            $user = $user."@".ldap_domain;
        }
        $bind = ldap_bind($this->con, $user, $pw);
        if ($bind) {
            return true;
        } else {
            throw new Exception("Usuário ou senha incorreta!");
            return false;
        }
    }
}
?>