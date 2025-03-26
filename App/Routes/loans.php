<?php

use App\Controllers\LoansController;
use Http\Router;

Router::get('/loans', [LoansController::class, 'getAll']);
Router::get('/loans/{id}', [LoansController::class, 'getById']);
Router::post('/loans', [LoansController::class, 'create']);
Router::post('/loans/desactive', [LoansController::class, 'desactive']);
Router::post('/loans/extend', [LoansController::class, 'extend']);
Router::delete('/loans/{id}', [LoansController::class, 'delete']);
