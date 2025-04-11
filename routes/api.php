<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UpvoteController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Models\User;

// ✅ Test Route
Route::get('/test', function () {
    return response()->json(['message' => 'Test route is working']);
})->withoutMiddleware('api');

//  Idea Routes
Route::apiResource('ideas', IdeaController::class);

//  User Routes
Route::get('/users', function () {
    return User::all();
});
Route::get('/users/{id}', function ($id) {
    return User::findOrFail($id);
});

//  Comment Routes
Route::get('/comments', [CommentController::class, 'all']);
Route::post('ideas/{idea}/comments', [CommentController::class, 'store']);
Route::get('ideas/{idea}/comments', [CommentController::class, 'index']);
Route::put('/comments/{comment}', [CommentController::class, 'update']);
Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

//  Upvote Routes
Route::post('ideas/{idea}/upvote', [UpvoteController::class, 'toggle']);
Route::get('ideas/{idea}/upvotes', [UpvoteController::class, 'index']);

// User Profile Routes (No auth)
Route::get('/users/{user}', [UserController::class, 'show']);
Route::put('/users/{user}', [UserController::class, 'update']);

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthenticationController::class, 'logout']);
