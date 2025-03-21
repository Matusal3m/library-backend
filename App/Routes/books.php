<?php

use App\Controllers\BooksController;
use Http\Router;

Router::get('/books', [BooksController::class, 'getAll']);
Router::get('/books/{id}', [BooksController::class, 'getById']);
Router::post('/books', [BooksController::class, 'create']);
Router::put('/books/{id}', [BooksController::class, 'update']);
Router::delete('/books/{id}', [BooksController::class, 'delete']);
