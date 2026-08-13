<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // POST /api/posts/{id}/like
    public function toggle(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        $user = $request->user();

        $like = Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        // إذا موجود → Unlike
        if ($like) {
            $like->delete();

            return response()->json([
                'message' => 'Post unliked successfully.',
                'liked' => false,
            ]);
        }

        // إذا غير موجود → Like
        Like::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        // إرسال إشعار لصاحب البوست
        if ($post->user_id !== $user->id) {
            Notification::create([
                'user_id' => $post->user_id,
                'sender_id' => $user->id,
                'post_id' => $post->id,
                'type' => 'LIKE',
                'message' => $user->username . ' liked your post.',
            ]);
        }

        return response()->json([
            'message' => 'Post liked successfully.',
            'liked' => true,
        ], 201);
    }
}
