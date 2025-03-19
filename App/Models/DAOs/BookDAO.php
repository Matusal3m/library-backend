<?php
namespace Library\App\Models\DAOs;

use Library\App\Models\Entities\Book;
use Library\App\Models\Mappers\BookMapper;
use Library\Database\Database;

class BookDAO
{
    public function __construct(private Database $db, private BookMapper $bookMapper)
    {
        $this->db         = $db;
        $this->bookMapper = $bookMapper;
    }

    public function save(Book $book): Book
    {
        $title        = $book->getTitle();
        $author_id    = $book->getAuthorId();
        $is_available = $book->getIsAvailable();
        $seduc_code   = $book->getSeducCode();

        $query =
            'INSERT INTO books
            (title, author_id, is_available, seduc_code)
            VALUES
            (:title, :author_id, :is_available, :seduc_code)';

        $binds = [
            'title'        => $title,
            'author_id'    => $author_id,
            'is_available' => $is_available,
            'seduc_code'   => $seduc_code,
        ];

        $this->db->prepareAndExec($query, $binds);
        $book->setId($this->db->lastInsertId());
        return $book;
    }

    public function getById(int $id): ?Book
    {
        $query = 'SELECT * FROM books WHERE id = :id';
        $binds = ['id' => $id];

        $bookRow = $this->db->prepareAndExec($query, $binds);

        $book = $this->bookMapper->mapRowToBook($bookRow);
        return $book;
    }

    public function getLoanById(int $loan_id): ?Book
    {
        $query = 'SELECT * FROM books WHERE loan_id = :loan_id';
        $binds = ['loan_id' => $loan_id];

        $bookRow = $this->db->prepareAndExec($query, $binds);

        $book = $this->bookMapper->mapRowToBook($bookRow);
        return $book;
    }

    public function getAllRaw(): array
    {
        $query = 'SELECT * FROM books';

        $booksRows = $this->db->query($query);

        return $booksRows;
    }

    public function getAllRawByAuthorId(int $authorId): ?array
    {
        $query = 'SELECT * FROM books WHERE author_id = :author_id';
        $binds = ['author_id' => $authorId];

        $booksRows = $this->db->prepareAndExec($query, $binds);

        return $booksRows;
    }

    public function getAllMappedByAuthorId(int $authorId): array
    {
        return array_map(
            fn($row) => $this->bookMapper->mapRowToBook($row),
            $this->getAllRawByAuthorId($authorId)
        );
    }

    public function getAllMapped(): array
    {
        return array_map(
            fn($row) => $this->bookMapper->mapRowToBook($row),
            $this->getAllRaw()
        );
    }

    public function update(Book $book): void
    {
        $id           = $book->getId();
        $title        = $book->getTitle();
        $author_id    = $book->getAuthorId();
        $is_available = $book->getIsAvailable();
        $seduc_code   = $book->getSeducCode();

        $query = 'UPDATE books
        SET
            title = :title,
            author_id = :author_id,
            is_available = :is_available,
            seduc_code = :seduc_code,
        WHERE id = :id';

        $binds = [
            'id'           => $id,
            'title'        => $title,
            'author_id'    => $author_id,
            'is_available' => $is_available,
            'seduc_code'   => $seduc_code,
        ];

        $this->db->prepareAndExec($query, $binds);
    }

    public function delete(int $id): void
    {
        $query = 'DELETE FROM books WHERE id = :id';
        $binds = ['id' => $id];

        $this->db->prepareAndExec($query, $binds);
    }
}
