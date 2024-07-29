<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;


Route::group([
  'middleware' => 'api',
  'prefix' => 'auth'
], function () {
  Route::post('register', [AuthController::class, 'register']);
  Route::post('login', [AuthController::class, 'login']);
  Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');
  Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:api');
});


Route::get('/tasks', [TaskController::class, 'findAll'])->middleware('auth:api');
Route::get('/tasks/{id}', [TaskController::class, 'findOne'])->middleware('auth:api');
Route::post('/tasks', [TaskController::class, 'create'])->middleware('auth:api');
Route::patch('/tasks/{id}', [TaskController::class, 'update'])->middleware('auth:api');
Route::delete('/tasks/{id}', [TaskController::class, 'delete'])->middleware('auth:api');
