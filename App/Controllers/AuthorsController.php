<?php
namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Services\AuthorsService;
use App\Validation\IdValidator;
use Exception;
use Http\Request;
use Http\Response;

class AuthorsController extends Controller
{
    public function __construct(
        private AuthorsService $authorService,
    ) {
        $this->authorService = $authorService;
    }

    public function create(Request $request, Response $response): void
    {
        $data = $request->body();

        try {
            $author = $this->authorService->createAuthor($data);

            $response->json([
                'id'   => $author->getId(),
                'name' => $author->getName(),
            ]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function update(Request $request, Response $response, array $id): void
    {
        $data = $request->body();

        try {
            $authorId = IdValidator::validateOne($id[0]);

            $this->authorService->updateAuthor($authorId, $data);

            $response->json(['message' => 'Author updated']);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getById(Request $request, Response $response, array $id): void
    {
        try {
            $authorId = IdValidator::validateOne($id[0]);

            $author = $this->authorService->getAuthor($authorId);

            $response->json([
                'name' => $author->getName(),
            ]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getAll(Request $request, Response $response): void
    {
        try {
            $author = $this->authorService->getAllAuthors();

            $response->json($author);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function delete(Request $request, Response $response, array $id): void
    {

        try {
            $authorId = IdValidator::validateOne($id[0]);

            $this->authorService->deleteAuthor($authorId);

            $response->json(['status' => 'sucess'], 200);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }
}
