<?php

use App\Controllers\DocumentsController;
use Http\Router;

Router::get('/document/loans', [DocumentsController::class, 'getLoansPdf']);
