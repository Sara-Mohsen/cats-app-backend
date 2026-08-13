<?php

namespace Database\Seeders;

use App\Models\AdoptionRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdoptionRequestSeeder extends Seeder
{
    public function run(): void
    {
        $sara = User::where('username', 'sara')->first();
        $ahmed = User::where('username', 'ahmed')->first();
        $nora = User::where('username', 'nora')->first();
        $faisal = User::where('username', 'faisal')->first();

        $oliver = Post::where('name', 'Oliver')
            ->where('post_type', 'ADOPTION')
            ->first();

        $bella = Post::where('name', 'Bella')
            ->where('post_type', 'ADOPTION')
            ->first();

        $requests = [
            [
                'user_id' => $sara->id,
                'post_id' => $oliver->id,
                'status' => 'PENDING',
            ],
            [
                'user_id' => $ahmed->id,
                'post_id' => $oliver->id,
                'status' => 'REJECTED',
            ],
            [
                'user_id' => $nora->id,
                'post_id' => $bella->id,
                'status' => 'APPROVED',
            ],
            [
                'user_id' => $faisal->id,
                'post_id' => $bella->id,
                'status' => 'CANCELLED',
            ],
        ];

        foreach ($requests as $request) {
            AdoptionRequest::create($request);
        }
    }
}
