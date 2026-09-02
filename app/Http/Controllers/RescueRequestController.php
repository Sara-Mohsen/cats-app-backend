<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Post;
use App\Models\RescueRequest;
use Illuminate\Http\Request;

class RescueRequestController extends Controller
{
    public function store(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        if (strtoupper($post->post_type) !== 'RESCUE') {
            return response()->json(['message' => 'This post is not a rescue case.'], 422);
        }

        if ($post->user_id === $request->user()->id) {
            return response()->json(['message' => 'You cannot request your own post.'], 403);
        }

        $existing = RescueRequest::where('user_id', $request->user()->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Request already submitted.'], 409);
        }

        $rescueRequest = RescueRequest::create([
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
            'status'  => 'PENDING',
        ]);

        Notification::create([
            'user_id'   => $post->user_id,
            'sender_id' => $request->user()->id,
            'post_id'   => $post->id,
            'type'      => 'RESCUE',
            'message'   => 'offered to rescue your cat.',
            'is_read'   => false,
        ]);

        return response()->json([
            'message' => 'Rescue request submitted successfully.',
            'request' => $rescueRequest,
        ], 201);
    }

    public function index(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        if ($post->post_type !== 'RESCUE') {
            return response()->json([
                'message' => 'This post is not a rescue case.'
            ], 422);
        }

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
