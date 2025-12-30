<?php

use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('throttle:60,1')->group(function () {
    Route::apiResource('projects', ProjectController::class);

    // for soft delete restore and force delete
    Route::patch('projects/{id}/restore', [ProjectController::class, 'restore']);
    Route::delete('projects/{id}/force', [ProjectController::class, 'forceDelete']);
});