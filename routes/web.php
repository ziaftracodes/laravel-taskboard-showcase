<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::resource('tasks', TaskController::class)->except(['show']);
Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');
