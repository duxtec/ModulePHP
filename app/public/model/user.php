<?php
namespace App\Model;

use Database\Entity\User as UserEntity;
use Resources\Utils\Response;
use Resources\Utils\Validator;
use Resources\Database\ORM;

class User
{
    public static function create(array $userData)
    {
        $required_fields = self::getRequiredFields();
        $validate = Validator::validateRequiredFields(
            $userData,
            $required_fields
        );
        if (!$validate["success"]) {
            $fields = implode(", ", $validate["data"]);
            $errorMessage = "Required fields missing: $fields";
            return Response::Error($errorMessage);
        }

        $user = new UserEntity();
        $user->setUsername($userData['username']);
        $user->setEmail($userData['email']);

        self::getEntityManager()->persist($user);
        self::getEntityManager()->flush();

        return $user;
    }

    public static function read(array $params)
    {
        $entityManager = self::getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        // Inicia a consulta com a entidade User
        $queryBuilder->select('u.id, u.username, u.email, u.registration_date')
            ->from(UserEntity::class, 'u');

        ORM::applyFilters($queryBuilder, $params);
        ORM::applySorters($queryBuilder, $params);

        $results = $queryBuilder->getQuery()->getArrayResult();

        // Executa a consulta e retorna os resultados
        return Response::Success($results);
    }


    public static function update(array $data)
    {
        $userId = $data["Id"];
        $user = self::getEntityManager()->getRepository(UserEntity::class)->find($userId);

        if (!$user) {
            return Response::Error("User not found");
        }

        $user->setUsername($data['username'] ?? $user->getUsername());
        $user->setEmail($data['email'] ?? $user->getEmail());

        self::getEntityManager()->flush();

        return $user;
    }

    public static function delete($userId): Response
    {
        $user = self::getEntityManager()->getRepository(UserEntity::class)->find($userId);

        if ($user) {
            self::getEntityManager()->remove($user);
            self::getEntityManager()->flush();
            return Response::Success(); // Usuário excluído com sucesso
        }

        return Response::Error("User not found"); // Usuário não encontrado
    }

    private static function getEntityManager()
    {
        global $M;
        return $M->EntityManager;
    }

    private static function getRequiredFields()
    {
        $classMetadata = self::getEntityManager()->getClassMetadata(UserEntity::class);
        $requiredFields = array_filter($classMetadata->fieldMappings, function ($mapping) {
            return isset ($mapping['nullable']) && !$mapping['nullable'];
        });
        return $requiredFields;
    }
}