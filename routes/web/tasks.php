<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::get('tasks/form-definition/{id?}', [App\Http\Controllers\TaskController::class, 'getModelFormDefinition'])
    ->name('tasks.form-definition');
Route::resource('tasks', TaskController::class);
Route::post('tasks/{task}/complete', [TaskController::class, 'completeTask'])->name('tasks.complete');