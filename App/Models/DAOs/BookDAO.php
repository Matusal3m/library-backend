<?php
namespace App\Models\DAOs;

use App\Models\Entities\Book;
use App\Models\Mappers\BookMapper;
use Database\Database;

class BookDAO
{
    public function __construct(
        private Database $db,
        private BookMapper $bookMapper
    ) {}

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

        $this->db->prepareAndExecute($query, $binds);
        $book->setId($this->db->lastInsertId());
        return $book;
    }

    public function getById(int $id): ?Book
    {
        $query = 'SELECT * FROM books WHERE id = :id';
        $binds = ['id' => $id];

        $bookRow = $this->db->prepareAndFetch($query, $binds);

        if (! $bookRow) {
            return null;
        }

        $book = $this->bookMapper->mapArrayToBook($bookRow[0]);
        return $book;
    }

    public function getAllRaw(): mixed
    {
        $query = 'SELECT * FROM books';

        $booksRows = $this->db->query($query);

        return $booksRows;
    }

    public function getAllRawByAuthorId(int $authorId): ?array
    {
        $query = 'SELECT * FROM books WHERE author_id = :author_id';
        $binds = ['author_id' => $authorId];

        $booksRows = $this->db->prepareAndFetch($query, $binds);

        return $booksRows;
    }

    public function getAllMappedByAuthorId(int $authorId): array
    {
        return array_map(
            fn($row) => $this->bookMapper->mapArrayToBook($row),
            $this->getAllRawByAuthorId($authorId)
        );
    }

    public function getAllMapped(): array
    {
        return array_map(
            fn($row) => $this->bookMapper->mapArrayToBook($row),
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
            seduc_code = :seduc_code
        WHERE id = :id';

        $binds = [
            'id'           => $id,
            'title'        => $title,
            'author_id'    => $author_id,
            'is_available' => $is_available,
            'seduc_code'   => $seduc_code,
        ];

        $this->db->prepareAndExecute($query, $binds);
    }

    public function delete(int $id): void
    {
        $query = 'DELETE FROM books WHERE id = :id';
        $binds = ['id' => $id];

        $this->db->prepareAndExecute($query, $binds);
    }
}
