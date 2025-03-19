<?php
namespace Library\bootstrap;

use Library\Database\DatabaseInit;

$dbInit = new DatabaseInit();

$dbInit->applyTables();
