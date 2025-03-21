<?php

use App\Controllers\BooksController;
use Http\Router;

Router::get('/books', [BooksController::class, 'create']);
