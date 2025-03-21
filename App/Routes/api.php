<?php

use Library\App\Controllers\BooksController;
use Library\Http\Router;

Router::get('/books', [BooksController::class, 'create']);
