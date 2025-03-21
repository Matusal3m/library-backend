<?php
namespace App\Controllers;

use Http\Request;
use Http\Response;

abstract class Controller
{
    abstract public function create(Request $request, Response $response): void;
    abstract public function update(Request $request, Response $response, array $id): void;
    abstract public function getById(Request $request, Response $response, array $id): void;
    abstract public function getAll(Request $request, Response $response): void;
    abstract public function delete(Request $request, Response $response, array $id): void;
}
