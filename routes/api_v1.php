<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\ProductCommentsController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserCommentsController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;


//Protected routes
Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('products', ProductController::class)->except('index', 'show');
    Route::apiResource('categories', CategoryController::class)->except('index', 'show');
    Route::apiResource('comments', CommentController::class)->except('index', 'show', 'store');

    //Optional routes with parent/child relations built in
    Route::apiResource('users.comments', UserCommentsController::class);
    Route::apiResource('products.comments', ProductCommentsController::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


//Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('products', ProductController::class)->only('index', 'show');
Route::apiResource('categories', CategoryController::class)->only('index', 'show');
Route::apiResource('comments', CommentController::class)->only('index', 'show', 'store');
