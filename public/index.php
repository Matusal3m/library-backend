<?php

use Dice\Dice;
use Http\Dispatcher;

require __DIR__ . '/../vendor/autoload.php';

// load api routes
require __DIR__ . '/../App/Routes/api.php';

$dice = new Dice();

$dice->addRule('Database', [
    'shared' => true,
]);

// handle request
$path       = $_SERVER['REQUEST_URI'];
$dispatcher = new Dispatcher();
$dispatcher->dispatch($path);
