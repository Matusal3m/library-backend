<?php
namespace Library\scripts;

use Library\Database\Database;
use Library\Database\DatabaseInit;

$db     = new Database();
$dbInit = new DatabaseInit($db);

$dbInit->applyTables();
