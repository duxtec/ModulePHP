#!/usr/bin/env php
<?php

use Resources\Audit\Lighthouse;
use Resources\Utils\Node;
use Resources\Utils\Shell;

$l = Shell::getOption(["l", "list"]);

if ($l) {
    var_dump(Lighthouse::list());
    exit();
} else {
    if (Node::isInstalled()) {
        Shell::printInfo("Node.js is installed. Running lighthouse...");
        Lighthouse::exec();
    } else {
        Shell::printError("Node.js is not installed. Lighthouse depends on Node.JS.");
        exit();
    }
}