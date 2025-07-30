<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Route;

// Redirect root to the API documentation
Route::get('/', function () {
    return redirect('/docs/api');
});
