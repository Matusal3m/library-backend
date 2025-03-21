<?php
namespace Library\App\Controllers;

use Library\Http\Request;
use Library\Http\Response;

abstract class Controller
{
    abstract public function create(Request $request, Response $response): void;
    abstract public function update(Request $request, Response $response): void;
    abstract public function getById(Request $request, Response $response): void;
    abstract public function getAll(Request $request, Response $response): void;
    abstract public function delete(Request $request, Response $response): void;
}
