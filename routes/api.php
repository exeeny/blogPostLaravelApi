<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/{type}/{id}/comment', [CommentController::class, "index"]);
Route::apiResource('/post', PostController::class)->middleware(['throttle:post']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::put('/user/profile', [ProfileController::class, 'update'])->middleware('auth:sanctum');

    Route::post('/{type}/{id}/comment', [CommentController::class, "store"]);
    Route::put('/comment/{comment}', [CommentController::class, "update"]);
    Route::delete('/comment/{comment}', [CommentController::class, "destroy"]);
});
