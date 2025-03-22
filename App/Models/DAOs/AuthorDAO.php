<?php
namespace App\Models\DAOs;

use App\Models\Entities\Author;
use App\Models\Mappers\AuthorMapper;
use Database\Database;

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

        $this->db->prepareAndExecute($query, $binds);
        $author->setId($this->db->lastInsertId());
        return $author;
    }

    public function getById(int $id): Author | bool
    {
        $query = 'SELECT * FROM authors WHERE id = :id';
        $binds = ['id' => $id];

        $authorRow = $this->db->prepareAndFetch($query, $binds);

        $author = $this->authorMapper->mapArrayToAuthor($authorRow[0]);
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
            fn($row) => $this->authorMapper->mapArrayToAuthor($row),
            $this->getAllRaw()
        );
    }

    public function update(Author $author): void
    {
        $id   = $author->getId();
        $name = $author->getName();

        $query = 'UPDATE authors SET name = :name WHERE id = :id';
        $binds = ['id' => $id, 'name' => $name];

        $this->db->prepareAndExecute($query, $binds);
    }

    public function delete(int $id): void
    {
        $query = 'DELETE FROM authors WHERE id = :id';
        $binds = ['id' => $id];

        $this->db->prepareAndExecute($query, $binds);
    }
}
