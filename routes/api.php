<?php

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public V2 Adapter API
Route::post('/v2', [ApiController::class, 'handle']);
Route::get('/v2', [ApiController::class, 'handle']); // Support GET for services list usually
