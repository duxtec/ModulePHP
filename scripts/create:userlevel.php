#!/usr/bin/env php
<?php

use Resources\Utils\Shell;
use Resources\Manager\User;

if (!class_exists('CreateUserlevel')) {
    class CreateUserlevel
    {
        private $argv;
        private $name;
        private $copy;


        public function __construct()
        {
            $args = Shell::getAllOptions();
            $this->argv = $args;
            $this->name = $args["default"];
            $this->copy = Shell::getOption(["c", "copy"]);
        }

        public function run()
        {
            try {
                // If userlevel is not provided, prompt the user
                if (!$this->name) {
                    $this->name = readline("Enter userlevel name:");
                }

                if (!$this->name) {
                    Shell::printError("Userlevel not provided.");
                    return;
                }

                $userLevelPath = APP_FOLDER . "$this->name";

                // Check if the userlevel directory already exists
                if (file_exists($userLevelPath)) {
                    Shell::printAlert("UserLevel folder already exists.");
                } else {
                    // If copy option is provided, copy the userlevel folder
                    if (!is_null($this->copy)) {
                        $this->copyUserlevelFolder();
                    } else {
                        $this->createUserlevelFolder();
                    }
                }

                $this->insertUserlevelInDatabase();
            } catch (Throwable $th) {
                // Print error message and stack trace
                Shell::printError("Error creating userlevel: " . $th->getMessage());
                Shell::printError($th->getTraceAsString());
                return;
            }
        }

        private function copyUserlevelFolder()
        {
            $source = APP_FOLDER . $this->copy;
            $destination = APP_FOLDER . $this->name;

            // Check if source userlevel folder exists
            if (!file_exists($source)) {
                Shell::printError("Userlevel folder to be copied '{$this->copy}' not found.");
                return;
            }

            Shell::printInfo("Copying userlevel '{$this->copy}' to '{$this->name}'...");

            // Copy userlevel folder recursively
            if (!$this->copydir($source, $destination)) {
                Shell::printError("Failed to copy folder from userlevel '{$this->copy}' to '{$this->name}'.");
                return;
            }

            Shell::printSuccess("UserLevel folder created successfully.");
        }

        private function createUserlevelFolder()
        {
            $path = APP_FOLDER . $this->name;
            $directories = [
                "/model",
                "/view/pages",
                "/view/modules",
                "/view/base/body",
                "/view/base/head",
                "/view/modules/components/tables",
                "/view/modules/components/forms",
                "/view/modules/components/viewers",
                "/view/modules/components/others",
                "/controller/create",
                "/controller/read",
                "/controller/update",
                "/controller/delete",
                "/assets/build",
                "/assets/src/js",
                "/assets/src/css",
                "/assets/src/img",
                "/assets/src/json",
                "/assets/src/video",
                "/assets/src/audio",
                "/assets/src/doc",
                "/assets/src/other"
            ];

            $files = [
                "/view/pages/index.php",
                "/view/base/body/aside.php",
                "/view/base/body/footer.php",
                "/view/base/body/header.php",
                "/view/base/body/main.php",
                "/view/base/head/favicon.php",
                "/view/base/head/metatag.php",
                "/view/base/head/other.php",
                "/view/base/head/script.php",
                "/view/base/head/style.php"
            ];

            Shell::printInfo("Creating folders for userlevel '{$this->name}'...");
            foreach ($directories as $directory) {
                mkdir($path . $directory, 0777, true);
            }

            Shell::printInfo("Creating files for userlevel '{$this->name}'...");
            foreach ($files as $file) {
                fclose(fopen($path . $file, "w"));
            }
            Shell::printSuccess("UserLevel folder created successfully.");
        }

        private function insertUserlevelInDatabase()
        {
            Shell::printInfo("Inserting userlevel '{$this->name}' into database...");
            $response = User::createUserlevel($this->name);

            if (!$response["success"]) {
                Shell::printError("Error inserting userlevel '{$this->name}' into database.");
                Shell::printError($response["message"]);
                return;
            }
            Shell::printSuccess("Userlevel '{$this->name}' entered into database successfully.");
        }

        private function copydir($source, $destination)
        {
            if (is_dir($source)) {
                mkdir($destination);
                $files = scandir($source);
                foreach ($files as $file) {
                    if ($file != "." && $file != "..") {
                        $this->copydir("$source/$file", "$destination/$file");
                    }
                }
            } elseif (file_exists($source)) {
                copy($source, $destination);
            }
            return true;
        }
    }
}


// Instantiate and run the CreateUserlevel class
(new CreateUserlevel())->run();