<?php

use Http\Dispatcher;

require_once __DIR__ . '/../vendor/autoload.php';

// load api routes
require_once __DIR__ . '/../App/Routes/api.php';

// handle request
$path       = $_SERVER['REQUEST_URI'];
$dispatcher = new Dispatcher();
$dispatcher->dispatch($path);
