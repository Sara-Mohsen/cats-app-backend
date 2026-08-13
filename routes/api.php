<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\RescueRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RequestController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
    ]);
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    // Posts
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    // Comments
    Route::get('/posts/{id}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{id}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // Likes
    Route::post('/posts/{id}/like', [LikeController::class, 'toggle']);

    // Adoption Requests
    Route::post(
        '/posts/{id}/adoption-requests',
        [AdoptionRequestController::class, 'store']
    );

    Route::get(
        '/posts/{id}/adoption-requests',
        [AdoptionRequestController::class, 'index']
    );

    // Rescue Requests
    Route::post(
        '/posts/{id}/rescue-requests',
        [RescueRequestController::class, 'store']
    );

    Route::get(
        '/posts/{id}/rescue-requests',
        [RescueRequestController::class, 'index']
    );

    // Notifications
    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    );

    Route::patch(
        '/notifications/{id}/read',
        [NotificationController::class, 'markAsRead']
    );

    Route::patch(
        '/notifications/read-all',
        [NotificationController::class, 'markAllAsRead']
    );

    // Adoption
    Route::patch(
        '/adoption-requests/{id}',
        [RequestController::class, 'updateAdoptionRequest']
    );

    // Rescue
    Route::patch(
        '/rescue-requests/{id}',
        [RequestController::class, 'updateRescueRequest']
    );

    //Create
    Route::post('/posts', [PostController::class, 'store']);
});
