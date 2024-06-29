#!/usr/bin/env php
<?php

use Resources\Utils\Shell;
use Resources\Database\ORM;
use Doctrine\ORM\Tools\SchemaTool;

try {
    $entityManager = ORM::createEntityManager();
    $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
    $schemaTool = new SchemaTool($entityManager);
    $schemaTool->updateSchema($metadata);

    Shell::printSuccess("Schema updated successfully!\n");
} catch (\Throwable $th) {
    Shell::printError("Error updating schema:");
    Shell::printError($th->getMessage());
}