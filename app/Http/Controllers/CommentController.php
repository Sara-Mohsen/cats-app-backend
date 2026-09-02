<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(int $id)
    {
        $post = Post::findOrFail($id);

        $comments = $post->comments()
            ->with('user')
            ->latest()
            ->get();

        return response()->json([
            'comments' => CommentResource::collection($comments),
        ]);
    }

    public function store(Request $request, int $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post = Post::findOrFail($id);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
            'content' => $request->content,
        ]);

        if ($post->user_id !== $request->user()->id) {
            Notification::create([
                'user_id' => $post->user_id,
                'sender_id' => $request->user()->id,
                'post_id' => $post->id,
                'type' => 'COMMENT',
                'message' =>' commented on your post.',
            ]);
        }

        $comment->load('user');

        return response()->json([
            'message' => 'Comment added successfully.',
            'comment' => new CommentResource($comment),
        ], 201);
    }

    public function destroy(Request $request, int $id)
    {
        $comment = Comment::findOrFail($id);

        $user = $request->user();

        if (!$user || $comment->user_id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to delete this comment.'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.'
        ]);
    }
}
