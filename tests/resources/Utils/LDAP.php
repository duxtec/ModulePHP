<?php

namespace Resources\Utils;

use Exception;

class LDAP
{
    public $con;
    private static $config;
    private static $host;
    private static $domain;
    private static $port;
    private static $user;
    private static $pw;

    public function __construct()
    {
        global $M;
        $this->config = $M->Config->ldap;
        $host = $this->config->HOST;
        $domain = $this->config->DOMAIN;
        $port = $this->config->PORT;
        if ($host && $domain && $port) {
            $this->con = ldap_connect(self::$host, self::$port);
            if (!$this->con) {
                throw new Exception("Database connection error");
            }
        } else {
            throw new Exception("LDAP not configured");
        }
    }

    public static function bind($user, $pw)
    {
        $con = (new self)->con;
        if (ldap_bind($con, $user, $pw)) {
            return true;
        } else {
            return false;
        }
    }

    public static function addUser($user, $gn, $sn, $o, $m, $p)
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

        $con = (new self)->con;
        if (ldap_add_ext($con, $dn, $info)) {
            return true;
        } else {
            throw new Exception("Error creating new LDAP user");
        }
    }

    public static function passwordVerify($user, $pw)
    {
        return (new self)->bind($user, $pw);
    }
}