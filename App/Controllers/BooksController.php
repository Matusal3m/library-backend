<?php
namespace App\Controllers;

use App\Models\DAOs\AuthorDAO;
use App\Models\DAOs\BookDAO;
use App\Models\Mappers\BookMapper;
use Exception;
use Http\Request;
use Http\Response;

class BooksController extends Controller
{

    public function __construct(
        private BookDAO $bookDAO,
        private AuthorDAO $authorDAO,
        private BookMapper $bookMapper
    ) {
        $this->bookDAO    = $bookDAO;
        $this->authorDAO  = $authorDAO;
        $this->bookMapper = $bookMapper;
    }

    public function create(Request $request, Response $response): void
    {
        $data = $request->body();

        if (! isset($data['title'], $data['author_id'], $data['seduc_code'])) {
            $response->json(['error' => 'Missing title, author_id or seduc_code in request'], 400);
            return;
        }

        if (! $this->authorDAO->getById($data['author_id'])) {
            $response->json(['error' => 'Author not found'], 404);
            return;
        }

        try {
            $book = $this->bookMapper->mapArrayToBook($data);

            $savedBook = $this->bookDAO->save($book);

            $response->json([
                'id'           => $savedBook->getId(),
                'title'        => $savedBook->getTitle(),
                'author_id'    => $savedBook->getAuthorId(),
                'seduc_code'   => $savedBook->getSeducCode(),
                'is_available' => $savedBook->getIsAvailable(),
            ], 201);

        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, Response $response, array $id): void
    {
        $bookId = $id[0];
        $data   = $request->body();

        if (! is_int($bookId)) {
            $response->json(['error' => 'Id must be an int'], 400);
            return;
        }

        $book = $this->bookDAO->getById($bookId);
        if (! $book) {
            $response->json(['error' => 'Book not found'], 404);
            return;
        }

        if (isset($data['title'])) {
            $book->updateTitle($data['title']);
        }

        if (isset($data['author_id'])) {

            if (! is_int($data['author_id'])) {
                $response->json(['error' => 'Author Id must be an int'], 400);
                return;
            }

            if (! $this->authorDAO->getById($data['author_id'])) {
                $response->json(['error' => 'Author not found'], 404);
                return;
            }
            $book->updateAuthorId($data['author_id']);
        }

        if (isset($data['seduc_code'])) {
            $book->setSeducCode($data['seduc_code']);
        }

        if (isset($data['is_available'])) {
            $book->setIsAvailable((bool) $data['is_available']);
        }

        try {
            $this->bookDAO->update($book);
            $response->json(['message' => 'Book updated']);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getById(Request $request, Response $response, array $id): void
    {
        $bookId = $id[0];
        if (! is_int($id[0])) {
            $response->json(['error' => 'Id must be an int'], 400);
            return;
        }

        try {

            $book = $this->bookDAO->getById($bookId);

            $response->json([
                'title'        => $book->getTitle(),
                'author_id'    => $book->getAuthorId(),
                'is_available' => $book->getIsAvailable(),
                'seduc_code'   => $book->getSeducCode(),
            ]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getAll(Request $request, Response $response): void
    {
        try {
            $data = $this->bookDAO->getAllRaw();

            $response->json($data);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, Response $response, array $id): void
    {
        $bookId = $id[0];

        if (! is_int($bookId)) {
            $response->json(['error' => 'Id must be an int'], 400);
            return;
        }

        $book = $this->bookDAO->getById($bookId);
        if (! $book) {
            $response->json(['error' => 'Book not found'], 404);
            return;
        }

        try {
            $this->bookDAO->delete($bookId);
            $response->json(['status' => 'sucess']);
        } catch (\Exception $e) {
            $response->json(['error' => $e->getMessage()], 500);
        }
    }
}
