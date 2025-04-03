<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UpvoteController;
use App\Models\User;


Route::get('/test', function () {
    return response()->json(['message' => 'Test route is working']);
})->withoutMiddleware('api');

Route::apiResource('ideas', IdeaController::class);

// Routes for Users
Route::get('/users', function () {
    return User::all();
});
Route::get('/users/{id}', function ($id) {
    return User::findOrFail($id);
});

// Routes for Comments
Route::get('/comments', [CommentController::class, 'all']);
Route::post('ideas/{idea}/comments', [CommentController::class, 'store']);
Route::get('ideas/{idea}/comments', [CommentController::class, 'index']);
Route::put('/comments/{comment}', [CommentController::class, 'update']);
Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

// Routes for Upvotes
Route::post('ideas/{idea}/upvote', [UpvoteController::class, 'toggle']);
Route::get('ideas/{idea}/upvotes', [UpvoteController::class, 'index']);
