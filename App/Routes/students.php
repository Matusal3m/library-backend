<?php
namespace App\Routes;

use App\Controllers\StudentsController;
use Http\Router;

Router::get('/students', [StudentsController::class, 'getAll']);
Router::get('/students/{id}', [StudentsController::class, 'getById']);
Router::post('/students', [StudentsController::class, 'create']);
Router::put('/students/{id}', [StudentsController::class, 'update']);
Router::delete('/Ssudents/{id}', [StudentsController::class, 'delete']);
