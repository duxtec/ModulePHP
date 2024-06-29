#!/usr/bin/env php

<?php

use Resources\Utils\Shell;
use Resources\Manager\User;
use Resources\Manager\Userlevel;
use Resources\Database\ORM;


if (!class_exists('DeleteUserlevel')) {
    class DeleteUserlevel
    {
        private $argv;
        private $name;
        public function __construct()
        {
            $args = Shell::getAllOptions();
            $this->argv = $args;
            $this->name = $args["default"];
        }

        public function run()
        {
            try {
                global $argv, $M;

                $userlevels_list = Userlevel::list();

                if (!count($userlevels_list)) {
                    Shell::printAlert("No userlevel found.");
                    return;
                }

                // If no user level was provided, prompt the user
                while (!$this->name || !in_array($this->name, $userlevels_list)) {
                    $this->name = Shell::showMenu("Select the userlevel to be deleted: ", $userlevels_list);
                }

                if (!isset($this->name)) {
                    Shell::printError("Userlevel not provided.");
                    return;
                }

                // Delete the user level from the database
                Shell::printInfo("Deleting userlevel '$this->name' from the database...");
                $M->entityManager = ORM::createEntityManager();

                $response = User::deleteUserlevel($this->name);

                if (!$response["success"]) {
                    Shell::printError("Error deleting userlevel '$this->name' from the database.");
                    Shell::printError($response["message"]);
                    return;
                }

                $this->name = $response["name"] ?? $this->name;

                Shell::printSuccess("Userlevel '$this->name' deleted from the database successfully.");

                // Check if the user level directory exists
                $path = APP_FOLDER . $this->name;

                if (!file_exists($path)) {
                    Shell::printError("Userlevel '$this->name' folder not found.");
                } else {
                    // Delete the user level directory and its contents
                    Shell::printInfo("Deleting userlevel '$this->name' folder...");
                    if (!$this->deleteDirectory($path)) {
                        Shell::printError("Failed to delete userlevel '$this->name' folder.");
                        return;
                    } else {
                        Shell::printSuccess("Userlevel '$this->name' folder deleted successfully.");
                    }
                }
            } catch (Exception $e) {
                Shell::printError("Error deleting userlevel.");
                Shell::printError("{$e->getMessage()} in {$e->getFile()}, line {$e->getLine()}");
            }
        }

        /**
         * Function to delete a directory and its contents recursively.
         *
         * @param string $dir The path to the directory to be deleted.
         * @return bool True if deletion is successful, false otherwise.
         */
        private function deleteDirectory($dir)
        {
            if (!file_exists($dir)) {
                return true;
            }

            if (!is_dir($dir)) {
                return unlink($dir);
            }

            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') {
                    continue;
                }
                if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                    return false;
                }
            }

            return rmdir($dir);
        }
    }
}

// Instantiate and run the CreateUserlevel class
(new DeleteUserlevel())->run();