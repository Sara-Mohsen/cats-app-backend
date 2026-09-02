<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;

class AdoptionRequestController extends Controller
{
    public function store(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        if (strtoupper($post->post_type) !== 'ADOPTION') {
            return response()->json(['message' => 'This post is not available for adoption.'], 422);
        }

        if ($post->user_id === $request->user()->id) {
            return response()->json(['message' => 'You cannot request your own post.'], 403);
        }

        $existing = AdoptionRequest::where('user_id', $request->user()->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Request already submitted.'], 409);
        }

        $adoptionRequest = AdoptionRequest::create([
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
            'status'  => 'PENDING',
        ]);

        Notification::create([
            'user_id'   => $post->user_id,
            'sender_id' => $request->user()->id,
            'post_id'   => $post->id,
            'type'      => 'ADOPTION',
            'message'   => 'submitted an adoption request for your cat.',
            'is_read'   => false,
        ]);

        return response()->json([
            'message' => 'Adoption request submitted successfully.',
            'request' => $adoptionRequest,
        ], 201);
    }

    public function index(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        if ($post->post_type !== 'ADOPTION') {
            return response()->json([
                'message' => 'This post is not an adoption post.'
            ], 422);
        }

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
