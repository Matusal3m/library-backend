<?php
namespace App\Controllers;

use Http\Request;
use Http\Response;

class BooksController extends Controller
{

    public function create(Request $request, Response $response): void
    {
        $data = $request->body();

        $response->json([
            'status' => 'sucess',
        ], 200);
    }

    public function update(Request $request, Response $response): void
    {
        // TODO
    }

    public function getById(Request $request, Response $response): void
    {
        // TODO
    }

    public function getAll(Request $request, Response $response): void
    {
        // TODO
    }

    public function delete(Request $request, Response $response): void
    {
        // TODO
    }
}
