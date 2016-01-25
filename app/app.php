<?php

//print_r($app['db']);

$app['db.connectionParams'] = array(
  'dbname' => 'taskdb',
  'user' => 'root',
  'password' => 'root',
  'host' => 'localhost',
  'driver' => 'pdo_mysql',
);

// Routes
$app->get('/', array('_controller' => 'Task\Controllers\MetroController::indexAction'));
$app->get('/import', array('_controller' => 'Task\Controllers\MetroController::importAction'));
$app->get('/station', array('_controller' => 'Task\Controllers\MetroController::getAll'));
$app->post('/station', array('_controller' => 'Task\Controllers\MetroController::newStation'));
$app->get('/station/{id}', array('_controller' => 'Task\Controllers\MetroController::getStation'));
$app->path('/station/{id}', array('_controller' => 'Task\Controllers\MetroController::updateStation'));
$app->delete('/station/{id}', array('_controller' => 'Task\Controllers\MetroController::deleteStation'));
$app->get('/station/line/{id}', array('_controller' => 'Task\Controllers\MetroController::getLineStation'));

return $app;