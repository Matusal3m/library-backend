<?php
require_once __DIR__ . '/../Database/Database.php';

use Database\Database;

$db = new Database();

// Inserir autores
$autores = [
    ['J.K. Rowling'],
    ['George Orwell'],
    ['Agatha Christie'],
];

foreach ($autores as $autor) {
    $stmt = $db->getConnection()->prepare('INSERT INTO authors (name) VALUES (:nome)');
    $stmt->bindValue(':nome', $autor[0], SQLITE3_TEXT);
    $stmt->execute();
}

// Inserir livros (assumindo autores inseridos na ordem acima)
$livros = [
    ['Harry Potter e a Pedra Filosofal', 1, 'HP123', true],
    ['1984', 2, 'GE1984', false],
    ['Murder on the Orient Express', 3, 'AC456', true],
];

foreach ($livros as $livro) {
    $stmt = $db->getConnection()->prepare('
        INSERT INTO books (title, author_id, seduc_code, is_available)
        VALUES (:titulo, :autor_id, :codigo, :disponivel)
    ');
    $stmt->bindValue(':titulo', $livro[0], SQLITE3_TEXT);
    $stmt->bindValue(':autor_id', $livro[1], SQLITE3_INTEGER);
    $stmt->bindValue(':codigo', $livro[2], SQLITE3_TEXT);
    $stmt->bindValue(':disponivel', $livro[3], SQLITE3_INTEGER); // SQLite não tem BOOLEAN, usa 0/1
    $stmt->execute();
}

// Inserir estudantes
$estudantes = [
    ['2023001', 'Alice Silva', 'A301', false],
    ['2023002', 'Bruno Costa', 'B205', true],
];

foreach ($estudantes as $estudante) {
    $stmt = $db->getConnection()->prepare('
        INSERT INTO students (enrollment_number, name, class_room, has_active_loan)
        VALUES (:matricula, :nome, :sala, :tem_emprestimo)
    ');
    $stmt->bindValue(':matricula', $estudante[0], SQLITE3_TEXT);
    $stmt->bindValue(':nome', $estudante[1], SQLITE3_TEXT);
    $stmt->bindValue(':sala', $estudante[2], SQLITE3_TEXT);
    $stmt->bindValue(':tem_emprestimo', $estudante[3], SQLITE3_INTEGER);
    $stmt->execute();
}

// Inserir empréstimos (assumindo IDs: livro 2, estudante 2)
$tempo_atual = time();
$emprestimos = [
    [
        'student_id'  => 2,
        'book_id'     => 2,
        'started_at'  => $tempo_atual - 86400 * 5,  // 5 dias atrás
        'finish_date' => $tempo_atual + 86400 * 10, // 10 dias futuros
        'extended_at' => 0,
    ],
];

foreach ($emprestimos as $emprestimo) {
    $stmt = $db->getConnection()->prepare('
        INSERT INTO loans (student_id, book_id, started_at, finish_date, extended_at)
        VALUES (:aluno_id, :livro_id, :inicio, :fim, :prorrogado)
    ');
    $stmt->bindValue(':aluno_id', $emprestimo['student_id'], SQLITE3_INTEGER);
    $stmt->bindValue(':livro_id', $emprestimo['book_id'], SQLITE3_INTEGER);
    $stmt->bindValue(':inicio', $emprestimo['started_at'], SQLITE3_INTEGER);
    $stmt->bindValue(':fim', $emprestimo['finish_date'], SQLITE3_INTEGER);
    $stmt->bindValue(':prorrogado', $emprestimo['extended_at'], SQLITE3_INTEGER);
    $stmt->execute();
}

echo "Seed concluído com sucesso!\n";
