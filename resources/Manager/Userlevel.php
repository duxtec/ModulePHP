<?php

namespace Resources\Manager;

use Resources\Database\DB as DB;
use Resources\Database\ORM;
use Resources\Database\Statement\Select;
use Resources\Database\Statement\Insert;
use Resources\Render\Json;
use Resources\Utils\Response;
use Database\Entity\User as UserEntity;
use Database\Entity\Userlevel as UserlevelEntity;
use Database\Entity\Session;
use Exception;

global $M;
class Userlevel
{

    public static function create($name)
    {
        try {
            global $M;

            $userlevel = new UserlevelEntity();
            $userlevel->setName($name);

            $M->entityManager = isset($M->entityManager) ? $M->entityManager : ORM::createEntityManager();
            $M->entityManager->persist($userlevel);
            $M->entityManager->flush();

            return Response::Success();
        } catch (\Throwable $th) {
            return Response::ErrorThrowable($th);
        }
    }

    public static function delete(string|int $nameOrId)
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
            $userLevelRepository = $M->entityManager->getRepository(UserlevelEntity::class);

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

    public static function list()
    {
        global $M;

        $M->entityManager = isset($M->entityManager) ? $M->entityManager : ORM::createEntityManager();
        $userLevels = $M->entityManager->getRepository(UserlevelEntity::class)->findAll();
        $userLevelNames = [];

        foreach ($userLevels as $userLevel) {
            $userLevelNames[] = $userLevel->getName();
        }
        return $userLevelNames;
    }
}