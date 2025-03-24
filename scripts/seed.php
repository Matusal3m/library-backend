<?php
require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');

use Database\Database;
use Database\DatabaseInit;

$db     = new Database();
$dbInit = new DatabaseInit($db);
$dbInit->applyTables();

$authors = [
    ['name' => 'Machado de Assis'],
    ['name' => 'Clarice Lispector'],
    ['name' => 'Jorge Amado'],
];

foreach ($authors as $author) {
    $db->prepareAndExecute(
        "INSERT INTO authors (name) VALUES (:name)",
        ['name' => $author['name']]
    );
}

$students = [
    [
        'enrollment_number' => '20231111',
        'name'              => 'João Silva',
        'class_room'        => '3A',
    ],
    [
        'enrollment_number' => '20232222',
        'name'              => 'Maria Souza',
        'class_room'        => '2B',
    ],
];

foreach ($students as $student) {
    $db->prepareAndExecute(
        "INSERT INTO students (enrollment_number, name, class_room)
            VALUES (:enrollment_number, :name, :class_room)",
        $student
    );
}

$books = [
    [
        'title'      => 'Dom Casmurro',
        'author_id'  => 1,
        'seduc_code' => 'LIT-001',
    ],
    [
        'title'      => 'A Hora da Estrela',
        'author_id'  => 2,
        'seduc_code' => 'LIT-002',
    ],
];

foreach ($books as $book) {
    $db->prepareAndExecute(
        "INSERT INTO books (title, author_id, seduc_code)
            VALUES (:title, :author_id, :seduc_code)",
        $book
    );
}

$loans = [
    [
        'student_id'  => 1,
        'book_id'     => 1,
        'started_at'  => date('d-m-y H:i'),
        'finish_date' => date('d-m-y H:i'),
        'extended_at' => null,
        'is_active'   => true,
    ],
];

foreach ($loans as $loan) {
    $db->prepareAndExecute(
        "INSERT INTO loans (student_id, book_id, started_at, finish_date, extended_at, is_active)
            VALUES (:student_id, :book_id, :started_at, :finish_date, :extended_at, :is_active)",
        [
            'student_id'  => $loan['student_id'],
            'book_id'     => $loan['book_id'],
            'started_at'  => $loan['started_at'],
            'finish_date' => $loan['finish_date'],
            'extended_at' => $loan['extended_at'],
            'is_active'   => (int) $loan['is_active'],
        ]
    );

    $db->prepareAndExecute(
        "UPDATE books SET is_available = 0 WHERE id = :book_id",
        ['book_id' => $loan['book_id']]
    );

    $db->prepareAndExecute(
        "UPDATE students SET has_active_loan = 1 WHERE id = :student_id",
        ['student_id' => $loan['student_id']]
    );
}

echo "Seed concluído com sucesso!\n";
