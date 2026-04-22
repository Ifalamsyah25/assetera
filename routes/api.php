<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->middleware('permission:project.read');
    Route::post('/projects', [ProjectController::class, 'store'])->middleware('permission:project.create');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])->middleware('permission:project.update');

    Route::get('/tasks', [TaskController::class, 'index'])->middleware('permission:task.read');
    Route::post('/tasks', [TaskController::class, 'store'])->middleware('permission:task.create');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->middleware('permission:task.update');
});
