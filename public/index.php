<?php
require __DIR__ . '/../vendor/autoload.php';

// load api routes
require __DIR__ . '/../App/Http/Routes/api.php';

use Library\Config\Router;

// handle request
$path = $_SERVER['REQUEST_URI'];
Router::dispatch($path);
