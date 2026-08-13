<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Post;
use App\Models\RescueRequest;
use Illuminate\Http\Request;

class RescueRequestController extends Controller
{
    // POST /api/posts/{id}/rescue-requests
    public function store(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        // لازم يكون المنشور بلاغ إنقاذ
        if ($post->post_type !== 'RESCUE') {
            return response()->json([
                'message' => 'This post is not a rescue case.'
            ], 422);
        }

        // لازم يكون البلاغ فعال
        if ($post->status !== 'ACTIVE') {
            return response()->json([
                'message' => 'This rescue case is no longer active.'
            ], 422);
        }

        // صاحب البلاغ ما يقدر يرسل طلب لنفسه
        if ($post->user_id === $request->user()->id) {
            return response()->json([
                'message' => 'You cannot submit a rescue request for your own post.'
            ], 403);
        }

        // التأكد أن المستخدم ما أرسل طلب من قبل
        $existingRequest = RescueRequest::where('user_id', $request->user()->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'You have already submitted a rescue request for this post.'
            ], 409);
        }

        $rescueRequest = RescueRequest::create([
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
            'status' => 'PENDING',
        ]);

        // إرسال إشعار لصاحب البلاغ
        Notification::create([
            'user_id' => $post->user_id,
            'sender_id' => $request->user()->id,
            'post_id' => $post->id,
            'type' => 'RESCUE_REQUEST',
            'message' => $request->user()->username . ' sent you a rescue request.',
        ]);

        return response()->json([
            'message' => 'Rescue request submitted successfully.',
            'request' => [
                'id' => $rescueRequest->id,
                'post_id' => $rescueRequest->post_id,
                'status' => $rescueRequest->status,
            ],
        ], 201);
    }

    // GET /api/posts/{id}/rescue-requests
    public function index(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        // نتأكد أن البوست إنقاذ
        if ($post->post_type !== 'RESCUE') {
            return response()->json([
                'message' => 'This post is not a rescue case.'
            ], 422);
        }

        // فقط صاحب البلاغ يقدر يشوف الطلبات
        if ($post->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to view these requests.'
            ], 403);
        }

        $requests = $post->rescueRequests()
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
