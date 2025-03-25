<?php
namespace App\Controllers;

use App\Models\Services\BooksService;
use App\Util\Converter;
use App\Validation\IdValidator;
use Exception;
use Http\Request;
use Http\Response;

class BooksController extends Controller
{

    public function __construct(
        private BooksService $booksService,
    ) {
        $this->booksService = $booksService;
    }

    public function create(Request $request, Response $response): void
    {
        $data = $request->body();

        try {
            $book = $this->booksService->createBook($data);

            $response->json([
                'id'           => $book->getId(),
                'title'        => $book->getTitle(),
                'author_id'    => $book->getAuthorId(),
                'seduc_code'   => $book->getSeducCode(),
                'genre'        => $book->getGenre(),
                'quantity'     => $book->getQuantity(),
                'is_available' => $book->getIsAvailable(),
            ], 201);

        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(Request $request, Response $response, array $id): void
    {
        $data = $request->body();

        try {
            $bookId = IdValidator::validateOne($id[0]);

            $this->booksService->updateBook($bookId, $data);

            $response->json(['message' => 'Book updated']);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function getById(Request $request, Response $response, array $id): void
    {
        try {
            $bookId = IdValidator::validateOne($id[0]);

            $book = $this->booksService->getBook($bookId);

            $response->json([
                'title'        => $book->getTitle(),
                'author_id'    => $book->getAuthorId(),
                'seduc_code'   => $book->getSeducCode(),
                'genre'        => $book->getGenre(),
                'quantity'     => $book->getQuantity(),
                'is_available' => $book->getIsAvailable(),
            ]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function getAll(Request $request, Response $response): void
    {
        try {
            $books = $this->booksService->getAllBooks();

            $books = Converter::convertKeysToBoolean($books, ['is_available']);

            $response->json($books);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function delete(Request $request, Response $response, array $id): void
    {
        try {
            $bookId = IdValidator::validateOne($id[0]);

            $this->booksService->deleteBook($bookId);

            $response->json(['status' => 'sucess']);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 500);
        }
    }
}
