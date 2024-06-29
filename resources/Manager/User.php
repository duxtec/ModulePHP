<?php

namespace Resources\Manager;

use Resources\Database\DB as DB;
use Resources\Database\ORM;
use Resources\Database\Statement\Select;
use Resources\Database\Statement\Insert;
use Resources\Render\Json;
use Resources\Utils\Response;
use Database\Entity\User as UserEntity;
use Database\Entity\Userlevel;
use Database\Entity\Session;
use Exception;

global $M;
class User
{

    public static function login($username, $password, $persistent = false)
    {
        if (!$user = self::getByUsername($username)) {
            return Response::Error("User does not exist!");
        }

        if (!password_verify($password, $user->getPassword())) {
            return Response::Error("Incorrect password!");
        }

        if (!self::createSession($user->getId(), $persistent)) {
            return Response::Error("Error logging in!");
        }

        return Response::Success();
    }
    public static function getByUsername($username)
    {
        global $M;
        $M->entityManager = isset($M->entityManager) ? $M->entityManager : ORM::createEntityManager();
        $userRepository = $M->entityManager->getRepository(UserEntity::class);
        $user = $userRepository->findOneBy(['username' => $username]);

        return $user;
    }

    public static function createSession($userid, $persistent)
    {
        $sessionid = session_id();
        $ip = User::capture_userip();

        if (!$userid || !$sessionid || !$ip) {
            $response["success"] = false;
            $response["status"] = "Erro ao construir sessão ou identificar IP!";
            return $response;
        }

        $newSession = new Session();

        $newSession->setUserId($userid)->setPhpSessionId($sessionid)->setUserIp($ip);

        try {
            $entityManager = ORM::createEntityManager();
            $entityManager->persist($newSession);
            $entityManager->flush();

            $_SESSION["userID"] = $userid;

            if ($persistent) {
                // Defina os cookies com tempo de expiração de 1 ano
                $expire = 60 * 60 * 24 * 365; // 1 ano = 365 dias.
                setcookie("userID", $userid, [
                    'expires' => time() + $expire,
                    'path' => '/',
                    'samesite' => 'Strict'
                ]);
                setcookie("PHPsession_id", $sessionid, [
                    'expires' => time() + $expire,
                    'path' => '/',
                    'samesite' => 'Strict'
                ]);
            }
            return true;
        } catch (Exception $e) {
            $response["success"] = false;
            $response["status"] = "Erro ao inciiar sessão!";
            return $response;
        }
    }


    public static function loginWithoutPass($username, $persistent = false)
    {
        $username = DB::escape($username);
        $sessionid = DB::escape(session_id());
        $ip = DB::escape(User::capture_userip());

        if (!$username || !$sessionid || !$ip) {
            return Response::Error("Error building session or identifying IP!");
        }

        $select = new Select();
        $select->table = "Users";
        $select->column("id", "password");

        $select->where("username", $username);
        $select->limit(1);

        $result = $select->result();

        if (!$result) {
            return Response::Error("User does not exist!");
        }

        $result = $result[0];

        $insert = new Insert("Sessions");
        $insert->column(
            "UserID",
            "PHPsession_id",
            "UserIP"
        );

        $insert->values($result["id"], $sessionid, $ip);

        if (!$insert->execute()) {
            return Response::Error("Error logging in!");
        }

        $_SESSION["userID"] = $result["id"];

        if ($persistent) {
            // Defina o valor do usuário
            $userID = $result["id"];

            // Defina o valor da sessão PHP
            $PHPsession_id = $sessionid;

            // Calcule o tempo de expiração em segundos para 1 ano
            $expire = 60 * 60 * 24 * 365; // 1 ano = 365 dias

            // Defina os cookies com tempo de expiração de 1 ano
            setcookie("userID", $userID, time() + $expire, "/");
            setcookie("PHPsession_id", $PHPsession_id, time() + $expire, "/");

        }
        return Response::Success();
    }

    public static function logout()
    {
        if (session_id()) {
            session_destroy();
        }
        header("location: /");
        exit();
    }

    public static function auth()
    {
        global $M;

        if (isset($_COOKIE['userID'])) {
            session_id($_COOKIE['PHPsession_id']);
            $_SESSION["userID"] = $_COOKIE['userID'];

        }

        session_start();

        $M->Config->cache();

        if (isset($_SESSION["userID"])) {
            $id = DB::escape($_SESSION["userID"]);
            $sessionid = DB::escape(session_id());
            $ip = DB::escape(User::capture_userip());

            if ($id && $sessionid && $ip) {

                $select = new Select("Userlevel");
                $select->column("Userlevel.name AS name");
                $select->join("Users", "Userlevel.id", "Users.userlevel_id");
                $select->join("Sessions", "Users.id", "Sessions.UserID");
                $select->where("Sessions.UserID", intval($id));
                $select->where("Sessions.PHPsession_id", $sessionid);
                $select->where("Sessions.UserIP", $ip);

                if ($result = $select->result()) {
                    return (object) array(
                        "id" => $id,
                        "userlevel" => $result[0]["name"]
                    );
                }
            }
        }

        return (object) array(
            "id" => 0,
            "userlevel" => "public"
        );
    }

    public static function recoveryPassword($username, $email, $message)
    {
        #Future feature;
        return true;
    }
    public static function newPassword($userid, $recoverycode, $password)
    {
        #Future feature;
        return true;
    }

    public static function capture_userip()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] as $xip) {
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

    public static function createOld($username, $email, $password, $userlevel, $active = true)
    {

        if (!$username || !$email || !$password || !$userlevel) {
            return Response::Error("Erro ao construir os dados do usuário!");
        }

        $userlevel_id = new Select("Userlevel");
        $userlevel_id->column("id");
        $userlevel_id->where("id", $userlevel, "=", "OR");
        $userlevel_id->where("name", $userlevel, "=", "OR");
        $userlevel_id = $userlevel_id->result();

        if (!$userlevel_id) {
            return Response::Error("Nível de usuário não encontrado!");
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $userlevel_id = $userlevel_id[0]['id'];

        $insert = new Insert("Users");
        $insert->column(
            "username",
            "email",
            "password",
            "userlevel_id",
            "active"
        );

        $insert->values(
            $username,
            $email,
            $hashed_password,
            $userlevel_id,
            $active
        );

        if (!$insert->execute()) {
            return Response::Error("Erro ao criar usuário!");
        }

        return true;
    }

    public static function create($username, $email, $password, int $userlevelName, $active = true)
    {
        try {
            global $M;

            $userLevelRepository = $M->entityManager->getRepository(Userlevel::class);
            $userLevel = $userLevelRepository->findOneBy(['id' => $userlevelName]);

            if ($userLevel === null) {
                return Response::Error("Userlevel not found!");
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $user = new UserEntity();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($hashedPassword);
            $user->setUserlevelId($userLevel->getId());
            $user->setActive($active);

            $M->entityManager = isset($M->entityManager) ? $M->entityManager : ORM::createEntityManager();
            $M->entityManager->persist($user);
            $M->entityManager->flush();

            return Response::Success();
        } catch (\Throwable $th) {
            return Response::Error($th->getTraceAsString());
        }
    }

    public static function createUserlevel($name)
    {
        try {
            global $M;

            $userlevel = new Userlevel();
            $userlevel->setName($name);

            $M->entityManager = isset($M->entityManager) ? $M->entityManager : ORM::createEntityManager();
            $M->entityManager->persist($userlevel);
            $M->entityManager->flush();

            return Response::Success();
        } catch (\Throwable $th) {
            return Response::ErrorThrowable($th);
        }
    }

    public static function deleteUserlevel(string|int $nameOrId)
    {
        try {
            global $M;

            // Check if the user level name or ID was provided
            if (!$nameOrId) {
                return Response::Error("Userlevel name or ID not provided!");
            }

            // Initialize the EntityManager if not defined
            $M->entityManager = $M->entityManager ?? ORM::createEntityManager();

            // Get the repository for the UserLevel entity
            $userLevelRepository = $M->entityManager->getRepository(UserLevel::class);

            // Check if the provided value is a number (ID) or a string (name)
            $criteria = is_numeric($nameOrId) ? ['id' => $nameOrId] : ['name' => $nameOrId];

            // Find the user level by name or ID
            $userLevel = $userLevelRepository->findOneBy($criteria);

            // Check if the user level exists in the database
            if (!$userLevel) {
                return Response::Error("Userlevel '$nameOrId' not found in the database.");
            }

            $userlevelName = $userLevel->getName();

            // Remove the user level and flush changes to the database
            $M->entityManager->remove($userLevel);
            $M->entityManager->flush();

            return Response::Success(["name" => $userlevelName]);
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return Response::ErrorThrowable($th);
        }
    }

}