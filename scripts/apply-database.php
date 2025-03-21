<?php
namespace scripts;

use Database\Database;
use Database\DatabaseInit;

$db     = new Database();
$dbInit = new DatabaseInit($db);

$dbInit->applyTables();
