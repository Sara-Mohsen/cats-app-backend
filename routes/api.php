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
use App\Models\City;
use App\Models\Breed;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json([
        'user' => $request->user()->load('city'),
    ]);
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/cities', function () {
    return response()->json(City::all());
});

Route::get('/breeds', function () {
    return response()->json(Breed::all());
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::get('/posts/{id}/comments', [CommentController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {

    Route::put('/me', [AuthController::class, 'updateProfile']);
    Route::post('/me', [AuthController::class, 'updateProfile']);

    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    Route::post('/posts/{id}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    Route::post('/posts/{id}/like', [LikeController::class, 'toggle']);
    Route::get('/favorites', [LikeController::class, 'favorites']);
    Route::get('/my-posts', [PostController::class, 'myPosts']);

    Route::patch('/notifications/{id}/status', [NotificationController::class, 'updateStatus']);

    Route::post(
        '/posts/{id}/adoption-requests',
        [AdoptionRequestController::class, 'store']
    );

    Route::get(
        '/posts/{id}/adoption-requests',
        [AdoptionRequestController::class, 'index']
    );

    Route::post(
        '/posts/{id}/rescue-requests',
        [RescueRequestController::class, 'store']
    );

    Route::get(
        '/posts/{id}/rescue-requests',
        [RescueRequestController::class, 'index']
    );

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

    Route::patch(
        '/notifications/{id}/status',
        [NotificationController::class, 'updateStatus']
    );

    Route::patch(
        '/adoption-requests/{id}',
        [RequestController::class, 'updateAdoptionRequest']
    );

    Route::patch(
        '/rescue-requests/{id}',
        [RequestController::class, 'updateRescueRequest']
    );
});
