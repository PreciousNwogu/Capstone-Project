<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UpvoteController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use App\Http\Controllers\PasswordResetController;

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'Test route is working']);
})->withoutMiddleware('api');

// Public routes
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);

// Protected routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('ideas', IdeaController::class)->except(['index', 'show']);
    Route::post('ideas/{idea}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('ideas/{idea}/upvote', [UpvoteController::class, 'toggle']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    
    // Email verification routes
    Route::post('/email/resend', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!']);
    })->name('verification.send');
});

// Route that requires signature (for email verification)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json(['message' => 'Email verified successfully.']);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// Public read-only routes
Route::get('ideas', [IdeaController::class, 'index']);
Route::get('ideas/{idea}', [IdeaController::class, 'show']);
Route::get('ideas/{idea}/comments', [CommentController::class, 'index']);
Route::get('ideas/{idea}/upvotes', [UpvoteController::class, 'index']);
Route::get('/comments', [CommentController::class, 'all']);

// User routes
Route::get('/users', function () {
    return User::first();
});
Route::get('/users/{id}', function ($id) {
    return User::findOrFail($id);
});