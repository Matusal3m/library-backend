<?php
namespace App\Models\Services;

use App\Models\DAOs\AuthorDAO;
use App\Models\Entities\Author;
use App\Models\Mappers\AuthorMapper;
use Exception;

class AuthorsService
{
    public function __construct(
        private AuthorMapper $authorMapper,
        private AuthorDAO $authorDAO
    ) {}

    public function createAuthor(array $data): Author
    {
        if (! isset($data['name'])) {
            throw new Exception('Author name is required', 400);
        }

        $author      = $this->authorMapper->mapArrayToAuthor($data);
        $savedAuthor = $this->authorDAO->save($author);

        return $savedAuthor;
    }

    public function updateAuthor(int $id, array $data): void
    {
        $author = $this->authorDAO->getById($id);

        if (! $author) {
            throw new Exception('Author not found', 404);
        }

        if (isset($data['name'])) {
            $author->updateName($data['name']);
        }

        $this->authorDAO->update($author);
    }

    public function getAuthor(int $id): Author
    {
        $author = $this->authorDAO->getById($id);

        if (! $author) {
            throw new Exception('Author not found', 404);
        }

        return $author;
    }

    public function getAllAuthors(): array
    {
        return $this->authorDAO->getAllRaw();
    }

    public function deleteAuthor(int $id): void
    {
        $author = $this->authorDAO->getById($id);

        if (! $author) {
            throw new Exception('Author not found', 404);
        }

        $this->authorDAO->delete($id);
    }

}
