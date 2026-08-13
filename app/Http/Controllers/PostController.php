<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with([
            'user',
            'breed',
            'city',
        ])
        ->latest()
        ->get();

        return response()->json([
            'posts' => PostResource::collection($posts),
        ]);
    }

    public function show(int $id)
    {
        $post = Post::with([
            'user',
            'breed',
            'city',
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
            'age_years' => 'sometimes|nullable|integer|min:0',
            'gender' => 'sometimes|in:MALE,FEMALE,UNKNOWN',
            'breed_id' => 'sometimes|nullable|exists:breeds,id',
            'city_id' => 'sometimes|exists:cities,id',
            'image_url' => 'sometimes|string',
            'personality_description' => 'sometimes|nullable|string',
            'is_neutered' => 'sometimes|nullable|boolean',
            'is_vaccinated' => 'sometimes|nullable|boolean',
            'is_injured' => 'sometimes|nullable|boolean',
            'injury_description' => 'sometimes|nullable|string',
            'contact_number' => 'sometimes|nullable|string|max:20',
            'status' => 'sometimes|in:ACTIVE,CLOSED',
        ]);

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
        $validated = $request->validate([
            'post_type' => 'required|in:NORMAL,ADOPTION,RESCUE',

            'name' => 'nullable|string|max:50',
            'age_years' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:MALE,FEMALE,UNKNOWN',

            'breed_id' => 'nullable|exists:breeds,id',
            'city_id' => 'required|exists:cities,id',

            'image_url' => 'required|string',

            'personality_description' => 'nullable|string',

            'is_neutered' => 'nullable|boolean',
            'is_vaccinated' => 'nullable|boolean',

            'is_injured' => 'nullable|boolean',
            'injury_description' => 'nullable|string',

            'contact_number' => 'nullable|string|max:20',
        ]);



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

            'image_url' => $validated['image_url'],

            'personality_description' =>
                $validated['personality_description'] ?? null,

            'is_neutered' => $validated['is_neutered'] ?? null,
            'is_vaccinated' => $validated['is_vaccinated'] ?? null,

            'is_injured' => $validated['is_injured'] ?? null,
            'injury_description' =>
                $validated['injury_description'] ?? null,

            'contact_number' =>
                $validated['contact_number'] ?? null,

            'status' => 'ACTIVE',
        ]);

        $post->load([
            'user',
            'breed',
            'city',
        ]);

        return response()->json([
            'message' => 'Post created successfully.',
            'post' => new PostResource($post),
        ], 201);
    }
}
