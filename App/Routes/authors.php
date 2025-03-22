<?php

use App\Controllers\AuthorsController;
use Http\Router;

Router::get('/authors', [AuthorsController::class, 'getAll']);
Router::get('/authors/{id}', [AuthorsController::class, 'getById']);
Router::post('/authors', [AuthorsController::class, 'create']);
Router::put('/authors/{id}', [AuthorsController::class, 'update']);
Router::delete('/authors/{id}', [AuthorsController::class, 'delete']);
