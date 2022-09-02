<?php

namespace ModulePHP;

use Exception;

class LDAP
{
    private $con;
    private static $server;
    private static $domain;
    private static $port;
    private static $user;
    private static $pw;

    function __construct()
    {
        global $M;
        self::$server = $M["Config"]["LDAP_SERVER"];
        self::$domain = $M["Config"]["LDAP_DOMAIN"];
        self::$port = $M["Config"]["LDAP_PORT"];
        self::$user = $M["Config"]["LDAP_USER"];
        self::$pw = $M["Config"]["LDAP_PW"];
        if (
            self::$server  &&
            self::$domain &&
            self::$port
        ) {
            $this->con = ldap_connect(self::$server, self::$port);
            if ($this->con) {
                return true;
            } else {
                throw new Exception("LDAP connection error");
                return false;
            }
        } else {
            throw new Exception("LDAP not configured");
            return false;
        }
    }

    function bind()
    {
        if (ldap_bind($this->con, self::$user, self::$pw)) {
            return true;
        } else {
            return false;
        }
    }

    function addUser($user, $gn, $sn, $o, $m, $p)
    {

        $info["cn"] = "$gn $sn";
        $info["givenName"] = "$gn";
        $info["sn"] = "$sn";
        $info["displayName"] = "$gn $sn";
        $info["company"] = "$o";
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
            throw new Exception("Error creating new LDAP user");
            return false;
        }
    }

    function passwordVerify($user, $pw)
    {
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            $user = $user;
        } else {
            $user = $user . "@" . self::$domain;
        }
        $bind = ldap_bind($this->con, $user, $pw);
        if ($bind) {
            return true;
        } else {
            return false;
        }
    }
}
