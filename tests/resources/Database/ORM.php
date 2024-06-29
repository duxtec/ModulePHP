<?php

namespace Resources\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\QueryBuilder;

class ORM
{
    public static function createEntityManager(): EntityManager
    {
        $includePath = get_include_path();

        $config = ORMSetup::createAttributeMetadataConfiguration(array("$includePath/database/orm"), true);

        global $M;
        $config = $M->Config;

        $host = $config->database->HOST;
        $user = $config->database->USER;
        $pw = $config->database->PW;
        $name = $config->database->NAME;
        $port = $config->database->PORT;
        $isDevMode = !$config->system->PRODUCTION;

        $paths = ["$includePath/database/entity"];

        // Configurações da conexão com o banco de dados
        $dbParams = [
            'driver' => 'pdo_mysql',
            'host' => $host,
            'user' => $user,
            'password' => $pw,
            'dbname' => $name,
        ];

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
        $connection = DriverManager::getConnection($dbParams, $config);
        return new EntityManager($connection, $config);
    }

    public static function getFilters(array $params): array
    {
        $filters = [];

        foreach ($params as $key => $value) {
            if ($value === '' || $key === 'sort') {
                continue; // Ignora filtros com valores vazios ou o parâmetro 'sort'
            }

            if (strpos($key, 'min_') === 0) {
                $field = substr($key, 4); // Remove 'min_' do início
                $filters[$field]['min'] = $value;
            } elseif (strpos($key, 'max_') === 0) {
                $field = substr($key, 4); // Remove 'max_' do início
                $filters[$field]['max'] = $value;
            } else {
                $filters[$key] = $value;
            }
        }

        return $filters;
    }

    public static function getSorters(array $params): array
    {
        $sorters = [];

        if (isset($params['sort'])) {
            $sortFields = explode(';', $params['sort']);
            foreach ($sortFields as $sortField) {
                list($field, $direction) = explode(',', $sortField);
                $sorters[] = [
                    'field' => $field,
                    'direction' => strtolower($direction) === 'desc' ? 'DESC' : 'ASC'
                ];
            }
        }

        return $sorters;
    }

    /**
     * Applies filters to the given QueryBuilder object based on the provided parameters.
     *
     * @param QueryBuilder $queryBuilder The QueryBuilder object to apply filters to.
     * @param array $params The parameters containing filters to be applied.
     */
    public static function applyFilters(QueryBuilder &$queryBuilder, array $params): void
    {
        // Obter a entidade raiz e seu alias
        $rootEntities = $queryBuilder->getRootEntities();
        $rootAlias = $queryBuilder->getRootAliases()[0]; // Assumindo que só há uma entidade raiz

        $filters = self::getFilters($params);
        foreach ($filters as $field => $value) {
            if (is_array($value)) {
                if (isset($value['min'])) {
                    $queryBuilder->andWhere("$rootAlias.$field >= :min_$field")
                        ->setParameter("min_$field", $value['min']);
                }
                if (isset($value['max'])) {
                    $queryBuilder->andWhere("$rootAlias.$field <= :max_$field")
                        ->setParameter("max_$field", $value['max']);
                }
            } else {
                $queryBuilder->andWhere("$rootAlias.$field = :$field")
                    ->setParameter($field, $value);
            }
        }
    }


    /**
     * Applies sorters to the given QueryBuilder object based on the provided parameters.
     *
     * @param QueryBuilder $queryBuilder The QueryBuilder object to apply sorters to.
     * @param array $params The parameters containing sorters to be applied.
     */

    public static function applySorters(QueryBuilder &$queryBuilder, array $params): void
    {
        // Obter a entidade raiz e seu alias
        $rootEntities = $queryBuilder->getRootEntities();
        $rootAlias = $queryBuilder->getRootAliases()[0]; // Assumindo que só há uma entidade raiz

        if (isset($params['sort'])) {
            $sortFields = explode(';', $params['sort']);
            foreach ($sortFields as $sortField) {
                list($field, $direction) = explode(',', $sortField);
                $sortDirection = strtolower($direction) === 'desc' ? 'DESC' : 'ASC';
                $queryBuilder->addOrderBy("$rootAlias.$field", $sortDirection);
            }
        }
    }
}