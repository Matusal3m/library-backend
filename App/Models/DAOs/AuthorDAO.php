<?php
namespace Library\App\Models\DAOs;

use Library\App\Models\Entities\Author;
use Library\App\Models\Mappers\AuthorMapper;
use Library\Database\Database;

class AuthorDAO
{
    public function __construct(private Database $db, private AuthorMapper $authorMapper)
    {
        $this->db           = $db;
        $this->authorMapper = $authorMapper;
    }

    public function save(Author $author): Author
    {
        $name = $author->getName();

        $query = 'INSERT INTO authors (name) VALUES (:name)';

        $binds = ['name' => $name];

        $this->db->prepareAndExec($query, $binds);
        $author->setId($this->db->lastInsertId());
        return $author;
    }

    public function getById(int $id): ?Author
    {
        $query = 'SELECT * FROM authors WHERE id = :id';
        $binds = ['id' => $id];

        $authorRow = $this->db->prepareAndExec($query, $binds);

        $author = $this->authorMapper->mapRowToAuthor($authorRow);
        return $author;
    }

    public function getAllRaw(): array
    {
        $query = 'SELECT * FROM authors';

        $authorsRows = $this->db->query($query);

        return $authorsRows;
    }

    public function getAllMapped(): array
    {
        return array_map(
            fn($row) => $this->authorMapper->mapRowToAuthor($row),
            $this->getAllRaw()
        );
    }

    public function update(Author $author): void
    {
        $id   = $author->getId();
        $name = $author->getName();

        $query = 'UPDATE authors SET name = :name WHERE id = :id';
        $binds = ['id' => $id, 'name' => $name];

        $this->db->prepareAndExec($query, $binds);
    }

    public function delete(int $id): void
    {
        $query = 'DELETE FROM authors WHERE id = :id';
        $binds = ['id' => $id];

        $this->db->prepareAndExec($query, $binds);
    }
}
