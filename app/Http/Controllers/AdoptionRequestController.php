<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;

class AdoptionRequestController extends Controller
{
    // POST /api/posts/{id}/adoption-requests
    public function store(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        if ($post->post_type !== 'ADOPTION') {
            return response()->json([
                'message' => 'This post is not available for adoption.'
            ], 422);
        }

        if ($post->status !== 'ACTIVE') {
            return response()->json([
                'message' => 'This post is no longer available for adoption.'
            ], 422);
        }

        // ممنوع صاحب المنشور يتبنى قطته
        if ($post->user_id === $request->user()->id) {
            return response()->json([
                'message' => 'You cannot submit an adoption request for your own post.'
            ], 403);
        }

        // التأكد أن المستخدم ما أرسل طلب من قبل
        $existingRequest = AdoptionRequest::where('user_id', $request->user()->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'You have already submitted an adoption request for this post.'
            ], 409);
        }

        $adoptionRequest = AdoptionRequest::create([
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
            'status' => 'PENDING',
        ]);

        // إرسال إشعار لصاحب البوست
        Notification::create([
            'user_id' => $post->user_id,
            'sender_id' => $request->user()->id,
            'post_id' => $post->id,
            'type' => 'ADOPTION_REQUEST',
            'message' => $request->user()->username . ' sent you an adoption request.',
        ]);

        return response()->json([
            'message' => 'Adoption request submitted successfully.',
            'request' => [
                'id' => $adoptionRequest->id,
                'post_id' => $adoptionRequest->post_id,
                'status' => $adoptionRequest->status,
            ],
        ], 201);
    }

    public function index(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        // نتأكد أن البوست تبني
        if ($post->post_type !== 'ADOPTION') {
            return response()->json([
                'message' => 'This post is not an adoption post.'
            ], 422);
        }

        // فقط صاحب البوست يقدر يشوف الطلبات
        if ($post->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to view these requests.'
            ], 403);
        }

        $requests = $post->adoptionRequests()
            ->with([
                'user:id,full_name,username,email,phone,avatar_url'
            ])
            ->latest()
            ->get();

        return response()->json([
            'requests' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'status' => $request->status,
                    'created_at' => $request->created_at,

                    'user' => [
                        'id' => $request->user->id,
                        'full_name' => $request->user->full_name,
                        'username' => $request->user->username,
                        'email' => $request->user->email,
                        'phone' => $request->user->phone,
                        'avatar_url' => $request->user->avatar_url,
                    ],
                ];
            }),
        ]);
    }
}
