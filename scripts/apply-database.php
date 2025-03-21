<?php
namespace scripts;

require __DIR__ . '/../vendor/autoload.php';

use Database\Database;
use Database\DatabaseInit;

$db     = new Database();
$dbInit = new DatabaseInit($db);

$dbInit->applyTables();
