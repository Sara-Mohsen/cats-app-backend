<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
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

        $likes = [
            [$sara->id, $milo->id],
            [$ahmed->id, $luna->id],
            [$nora->id, $luna->id],
            [$faisal->id, $oliver->id],
            [$sara->id, $oliver->id],
            [$ahmed->id, $bella->id],
            [$nora->id, $bella->id],
        ];

        foreach ($likes as [$userId, $postId]) {
            Like::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
        }
    }
}
