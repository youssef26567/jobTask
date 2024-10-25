<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'store']);
Route::get('/login', [UserController::class, 'login']);
Route::post('/verify-code', [UserController::class, 'verifyCode']);

Route::apiResource('tags', TagController::class)->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::get('posts/deleted', [PostController::class, 'deletedPosts']);
    Route::post('posts/{id}/restore', [PostController::class, 'restore']);
});
Route::get('/stats', [StatsController::class, 'index']);
