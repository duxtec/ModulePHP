<?php

use ModulePHP\MVC;

$id = $_POST['id'];

MVC::Model("example");

$example = new Example;
$read = $example->read($id);
$response["name"] = $read;

MVC::JsonResponse($response);