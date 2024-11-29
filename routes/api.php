<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\JobsController;
use App\Http\Middleware\QueryString;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('login',[AuthController::class,'login']);
Route::post('register',[AuthController::class,'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::get('posts', [PostController::class, 'posts']);
Route::middleware('auth:api')->post('/posts', [PostController::class, 'store']);
Route::middleware('auth:api')->put('/posts/{id}', [PostController::class, 'update']);
Route::middleware('auth:api')->delete('/posts/{id}', [PostController::class, 'destroy']);
Route::get('/posts/{post_id}/comments', [CommentsController::class, 'index']);
