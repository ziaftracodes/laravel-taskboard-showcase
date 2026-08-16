<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TaskApiController;

/*
|--------------------------------------------------------------------------
| API Routes — v1
|--------------------------------------------------------------------------
| Prefix: /api/v1
| All routes return JSON and are stateless.
|
*/

Route::prefix('v1')->name('api.')->group(function () {
    // Task CRUD (will be named api.tasks.index, api.tasks.store, etc.)
    Route::apiResource('tasks', TaskApiController::class);
    Route::patch('tasks/{task}/toggle', [TaskApiController::class, 'toggle'])->name('tasks.toggle');

    // Lookups
    Route::get('categories', [TaskApiController::class, 'categories'])->name('categories.index');
    Route::get('tags', [TaskApiController::class, 'tags'])->name('tags.index');
});
