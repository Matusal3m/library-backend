<?php

use Http\Dispatcher;

date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/../vendor/autoload.php';

// load api routes
require_once __DIR__ . '/../App/Routes/api.php';

// handle request
$path       = $_SERVER['REQUEST_URI'];
$dispatcher = new Dispatcher();
$dispatcher->dispatch($path);
