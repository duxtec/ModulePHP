#!/usr/bin/env php
<?php

use Resources\Utils\Loader;
use Resources\Utils\Node;
use Resources\Utils\Builder;
use Resources\Utils\Shell;

$m = Shell::getOption(["m", "mode"], "node");

if (Node::isInstalled() && $m !== "php") {
    Shell::printInfo("Running build with PHP...\n");
    Builder::assets();
    #Node::build();
} else {
    Shell::printInfo("Running build with PHP...\n");
    Builder::assets();
}