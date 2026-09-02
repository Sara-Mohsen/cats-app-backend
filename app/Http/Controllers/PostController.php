<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\RescueRequest;
use App\Models\Notification;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        $query = Post::with([
            'user',
            'breed',
            'city',
            'likes' => function ($q) use ($user) {
                if ($user) {
                    $q->where('user_id', $user->id);
                }
            }
        ])->latest();

        if ($request->has('type')) {
            $query->where('post_type', $request->type);
        }

        $posts = $query->get();

        return response()->json([
            'posts' => PostResource::collection($posts),
        ]);
    }

    public function show(Request $request, int $id)
    {
        $user = auth('sanctum')->user();

        $post = Post::with([
            'user',
            'breed',
            'city',
            'likes' => function ($q) use ($user) {
                if ($user) {
                    $q->where('user_id', $user->id);
                }
            }
        ])->findOrFail($id);

        return new PostResource($post);
    }

        public function update(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        $user = $request->user();

        if (!$user || $post->user_id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to update this post.'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|nullable|string|max:50',
            'age' => 'sometimes|nullable|integer|min:0|max:30',
            'gender' => 'sometimes|in:MALE,FEMALE,UNKNOWN',
            'breed_id' => 'sometimes|nullable|exists:breeds,id',
            'city_id' => 'sometimes|exists:cities,id',

            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',

            'personality' => 'sometimes|nullable|string',
            'is_neutered' => 'sometimes|nullable|boolean',
            'is_vaccinated' => 'sometimes|nullable|boolean',
            'is_injured' => 'sometimes|nullable|boolean',
            'injury_description' => 'sometimes|nullable|string',
            'contact_number' => 'sometimes|nullable|string|max:20',
            'status' => 'sometimes|in:ACTIVE,CLOSED',
        ]);

        if ($request->has('age')) {
            $validated['age_years'] = $validated['age'];
            unset($validated['age']);
        }

        if ($request->has('personality')) {
            $validated['personality_description'] = $validated['personality'];
            unset($validated['personality']);
        }

        if ($request->hasFile('image')) {

            if ($post->image_url) {
                Storage::disk('public')->delete($post->image_url);
            }

            $path = $request->file('image')->store('posts', 'public');

            $validated['image_url'] = $path;
        }

        unset($validated['image']);

        $post->update($validated);

        $post->load([
            'user',
            'breed',
            'city',
        ]);

        return response()->json([
            'message' => 'Post updated successfully.',
            'post' => new PostResource($post),
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        $user = $request->user();

        if (!$user || $post->user_id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to delete this post.'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.'
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_neutered' => $request->has('is_neutered') ? filter_var($request->is_neutered, FILTER_VALIDATE_BOOLEAN) : null,
            'is_vaccinated' => $request->has('is_vaccinated') ? filter_var($request->is_vaccinated, FILTER_VALIDATE_BOOLEAN) : null,
            'is_injured' => $request->has('is_injured') ? filter_var($request->is_injured, FILTER_VALIDATE_BOOLEAN) : null,
        ]);

        $validated = $request->validate([
            'post_type' => 'required|in:NORMAL,ADOPTION,RESCUE',

            'name' => 'nullable|string|max:50',
            'age_years' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:MALE,FEMALE,UNKNOWN',

            'breed_id' => 'nullable|exists:breeds,id',
            'city_id' => 'required|exists:cities,id',

            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',

            'personality_description' => 'nullable|string',

            'is_neutered' => 'nullable|boolean',
            'is_vaccinated' => 'nullable|boolean',

            'is_injured' => 'nullable|boolean',
            'injury_description' => 'nullable|string',

            'contact_number' => 'nullable|string|max:20',
        ]);

        $imageUrl = null;

        $path = $request->file('image')->store('posts', 'public');
        $imageUrl = $path;

        if ($validated['post_type'] === 'RESCUE') {
            $validated['name'] = null;
            $validated['age_years'] = null;
            $validated['gender'] = null;
            $validated['personality_description'] = null;
            $validated['is_neutered'] = null;
            $validated['is_vaccinated'] = null;

            if (empty($validated['is_injured'])) {
                $validated['injury_description'] = null;
            }
        }

        if ($validated['post_type'] !== 'RESCUE') {
            $validated['is_injured'] = null;
            $validated['injury_description'] = null;
        }

        $post = Post::create([
            'user_id' => $request->user()->id,
            'post_type' => $validated['post_type'],
            'name' => $validated['name'] ?? null,
            'age_years' => $validated['age_years'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'breed_id' => $validated['breed_id'] ?? null,
            'city_id' => $validated['city_id'],
            'image_url' => $imageUrl,
            'personality_description' => $validated['personality_description'] ?? null,
            'is_neutered' => $validated['is_neutered'] ?? null,
            'is_vaccinated' => $validated['is_vaccinated'] ?? null,
            'is_injured' => $validated['is_injured'] ?? null,
            'injury_description' => $validated['injury_description'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'status' => 'ACTIVE',
        ]);

        $post->load(['user', 'breed', 'city']);

        $rescueCode = null;
        if ($post->post_type === 'RESCUE') {
            $rescueCode = '#REC-' . str_pad($post->id, 4, '0', STR_PAD_LEFT);
        }

        return response()->json([
            'message' => 'Post created successfully.',
            'rescue_code' => $rescueCode,
            'post' => new PostResource($post),
        ], 201);
    }

    public function myPosts(Request $request)
    {
        try {
            $user = $request->user();

            $posts = Post::where('user_id', $user->id)
                ->with(['city', 'breed'])
                ->latest()
                ->get();

            return response()->json([
                'data' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function action(Request $request, int $id)
    {
        $user = $request->user();

        $post = Post::findOrFail($id);

        $request->validate([
            'type' => 'required|in:rescue',
        ]);

        if ($post->user_id === $user->id) {
            return response()->json([
                'message' => 'You cannot rescue your own post.'
            ], 403);
        }

        if ($post->post_type !== 'RESCUE') {
            return response()->json([
                'message' => 'This post is not a rescue post.'
            ], 400);
        }

        if ($post->status === 'CLOSED') {
            return response()->json([
                'message' => 'This rescue case is already closed.'
            ], 400);
        }

        $existingRequest = RescueRequest::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'You already sent a rescue request.'
            ], 409);
        }

        $rescueRequest = RescueRequest::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'status' => 'PENDING',
        ]);

        Notification::create([
            'user_id' => $post->user_id,
            'sender_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'rescue',
            'message' => $user->username . ' sent a rescue request for your case.',
            'is_read' => false,
        ]);

        return response()->json([
            'message' => 'Rescue request sent successfully.',
            'request' => [
                'id' => $rescueRequest->id,
                'post_id' => $rescueRequest->post_id,
                'status' => $rescueRequest->status,
            ],
        ], 201);
    }
}
