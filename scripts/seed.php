<?php
namespace scripts;

require __DIR__ . '/../vendor/autoload.php';

use Database\Database;
use Database\DatabaseInit;
use Database\DatabaseSeeder;

$db            = new Database();
$dbInitializer = new DatabaseInit($db);
$seeder        = new DatabaseSeeder($db);

$dbInitializer->applyTables();

$seeder->configure([
    'authors'               => 5,
    'books_per_author'      => 5,
    'students'              => 40,
    'active_loans_percent'  => 15,
    'max_books_per_student' => 1,
])->seed();
