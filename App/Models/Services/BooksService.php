<?php
namespace App\Models\Services;

use App\Models\DAOs\AuthorDAO;
use App\Models\DAOs\BookDAO;
use App\Models\Entities\Book;
use App\Models\Mappers\BookMapper;
use App\Validation\IdValidator;
use Exception;

class BooksService
{
    public function __construct(
        private BookDAO $bookDAO,
        private AuthorDAO $authorDAO,
        private BookMapper $bookMapper
    ) {}

    public function createBook(array $data): Book
    {
        if (! isset(
            $data['title'],
            $data['author_id'],
            $data['seduc_code'],
            $data['genre'],
            $data['quantity']
        )) {
            throw new Exception(
                'Missing title, author_id, seduc_code, genre and/or quantity in request',
                400
            );
        }

        $author = $this->authorDAO->getById($data['author_id']);
        if (! $author) {
            throw new Exception('Author not found', 404);
        }

        $book = $this->bookMapper->mapArrayToBook([ ...$data, 'is_available' => true]);
        return $this->bookDAO->save($book);
    }

    public function updateBook(int $id, array $data): void
    {
        $book = $this->bookDAO->getById($id);
        if (! $book) {
            throw new Exception('Book not found', 404);
        }

        if (isset($data['title'])) {
            $book->setTitle($data['title']);
        }

        if (isset($data['genre'])) {
            $book->setGenre($data['genre']);
        }

        if (isset($data['quantity'])) {
            $book->setQuantity($data['quantity']);
        }

        if (isset($data['author_id'])) {
            $author_id = IdValidator::validateOne($data['author_id']);

            $author = $this->authorDAO->getById($author_id);
            if (! $author) {
                throw new Exception('Author not found', 404);
            }
            $book->updateAuthorId($author_id);
        }

        if (isset($data['seduc_code'])) {
            $book->setSeducCode($data['seduc_code']);
        }

        if (isset($data['is_available'])) {
            $book->setIsAvailable((bool) $data['is_available']);
        }

        $this->bookDAO->update($book);
    }

    public function getBook(int $id): Book
    {
        $book = $this->bookDAO->getById($id);
        if (! $book) {
            throw new Exception('Book not found', 404);
        }
        return $book;
    }

    public function getAllBooks(): array
    {
        return $this->bookDAO->getAllRaw();
    }

    public function deleteBook(int $id): void
    {
        $book = $this->bookDAO->getById($id);
        if (! $book) {
            throw new Exception('Book not found', 404);
        }
        $this->bookDAO->delete($id);
    }
}
