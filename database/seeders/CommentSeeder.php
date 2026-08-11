<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $sara = User::where('username', 'sara')->first();
        $ahmed = User::where('username', 'ahmed')->first();
        $nora = User::where('username', 'nora')->first();
        $faisal = User::where('username', 'faisal')->first();

        $luna = Post::where('name', 'Luna')->first();
        $milo = Post::where('name', 'Milo')->first();
        $oliver = Post::where('name', 'Oliver')->first();
        $bella = Post::where('name', 'Bella')->first();

        $comments = [
            [
                'user_id' => $ahmed->id,
                'post_id' => $luna->id,
                'content' => 'She looks adorable! Is she still available?',
            ],
            [
                'user_id' => $nora->id,
                'post_id' => $luna->id,
                'content' => 'Her eyes are so beautiful 🥹',
            ],
            [
                'user_id' => $sara->id,
                'post_id' => $milo->id,
                'content' => 'Milo looks very calm and friendly.',
            ],
            [
                'user_id' => $faisal->id,
                'post_id' => $oliver->id,
                'content' => 'Is Oliver good with other cats?',
            ],
            [
                'user_id' => $sara->id,
                'post_id' => $oliver->id,
                'content' => 'He is so cute! I hope he finds a loving home.',
            ],
            [
                'user_id' => $ahmed->id,
                'post_id' => $bella->id,
                'content' => 'She looks very playful!',
            ],
            [
                'user_id' => $nora->id,
                'post_id' => $bella->id,
                'content' => 'What a beautiful Siamese cat ❤️',
            ],
        ];

        foreach ($comments as $comment) {
            Comment::create($comment);
        }
    }
}
