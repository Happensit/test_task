<?php

ini_set("display_errors",1);
error_reporting(E_ALL);

require_once __DIR__.'/../vendor/autoload.php';

$app = new \Task\Application();

require_once __DIR__ . '/../app/app.php';

$response = $app->run();
$response->send();