<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $sara = User::where('username', 'sara')->first();
        $ahmed = User::where('username', 'ahmed')->first();
        $nora = User::where('username', 'nora')->first();
        $faisal = User::where('username', 'faisal')->first();

        $luna = Post::where('name', 'Luna')->first();
        $oliver = Post::where('name', 'Oliver')->first();

        $rescuePost = Post::where('post_type', 'RESCUE')->first();

        $notifications = [
            [
                'user_id' => $sara->id,
                'sender_id' => $ahmed->id,
                'post_id' => $luna->id,
                'type' => 'LIKE',
                'message' => 'Ahmed liked your post about Luna.',
                'is_read' => false,
            ],

            [
                'user_id' => $sara->id,
                'sender_id' => $nora->id,
                'post_id' => $luna->id,
                'type' => 'COMMENT',
                'message' => 'Nora commented on your post about Luna.',
                'is_read' => false,
            ],

            [
                'user_id' => $nora->id,
                'sender_id' => $sara->id,
                'post_id' => $oliver->id,
                'type' => 'ADOPTION_REQUEST',
                'message' => 'Sara sent an adoption request for Oliver.',
                'is_read' => false,
            ],

            [
                'user_id' => $nora->id,
                'sender_id' => $ahmed->id,
                'post_id' => $oliver->id,
                'type' => 'ADOPTION_REQUEST',
                'message' => 'Ahmed sent an adoption request for Oliver.',
                'is_read' => true,
            ],

            [
                'user_id' => $sara->id,
                'sender_id' => $nora->id,
                'post_id' => $rescuePost->id,
                'type' => 'RESCUE_REQUEST',
                'message' => 'Nora offered to help rescue the cat.',
                'is_read' => false,
            ],

            [
                'user_id' => $faisal->id,
                'sender_id' => null,
                'post_id' => null,
                'type' => 'SYSTEM',
                'message' => 'Welcome to Cats Library! Your account is ready.',
                'is_read' => true,
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}
