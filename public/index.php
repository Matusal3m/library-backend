<?php

require __DIR__ . '/../vendor/autoload.php';

// load api routes
require __DIR__ . '/../App/Routes/api.php';

use Http\Router;

// handle request
$path = $_SERVER['REQUEST_URI'];
Router::dispatch($path);
