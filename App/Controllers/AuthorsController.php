<?php
namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\DAOs\AuthorDAO;
use App\Models\Mappers\AuthorMapper;
use Exception;
use Http\Request;
use Http\Response;

class AuthorsController extends Controller
{
    public function __construct(
        private AuthorMapper $authorMapper,
        private AuthorDAO $authorDAO
    ) {
        $this->authorMapper = $authorMapper;
        $this->authorDAO    = $authorDAO;
    }

    public function create(Request $request, Response $response): void
    {
        $data = $request->body();

        if (! isset($data['name'])) {
            $response->json(['error' => 'Author name is required'], 400);
            return;
        }

        $author = $this->authorMapper->mapArrayToAuthor($data);

        $savedAuthor = $this->authorDAO->save($author);

        $response->json([
            'id'   => $savedAuthor->getId(),
            'name' => $savedAuthor->getName(),
        ]);
    }

    public function update(Request $request, Response $response, array $id): void
    {
        $authorId = $id[0];
        $data     = $request->body();

        if (! filter_var($authorId, FILTER_VALIDATE_INT)) {
            $response->json(['error' => 'Id must be an int'], 400);
            return;
        }

        $author = $this->authorDAO->getById($authorId);

        if (! $author) {
            $response->json(['error' => 'Author not found'], 404);
            return;
        }

        if (isset($data['name'])) {
            $author->updateName($data['name']);
        }

        try {
            $this->authorDAO->update($author);
            $response->json(['message' => 'Author updated']);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getById(Request $request, Response $response, array $id): void
    {
        $authorId = $id[0];

        if (! filter_var($authorId, FILTER_VALIDATE_INT)) {
            $response->json(['error' => 'Id must be an int'], 400);
            return;
        }

        try {
            $book = $this->authorDAO->getById($authorId);

            $response->json([
                'name' => $book->getName(),
            ]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getAll(Request $request, Response $response): void
    {
        try {
            $data = $this->authorDAO->getAllRaw();

            $response->json($data);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, Response $response, array $id): void
    {
        $authorId = $id[0];

        if (! filter_var($authorId, FILTER_VALIDATE_INT)) {
            $response->json(['error' => 'Id must be an int'], 400);
            return;
        }

        $author = $this->authorDAO->getById($authorId);

        if (! $author) {
            $response->json(['error' => 'Author not found'], 404);
            return;
        }

        try {
            $this->authorDAO->delete($authorId);

            $response->json(['status' => 'sucess'], 200);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 400);
        }
    }
}
