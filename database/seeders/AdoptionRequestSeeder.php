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
                'message' => 'I would love to adopt Oliver. I have experience taking care of cats and can provide him with a safe home.',
                'status' => 'PENDING',
            ],
            [
                'user_id' => $ahmed->id,
                'post_id' => $oliver->id,
                'message' => 'I am interested in adopting Oliver and would be happy to give him a loving home.',
                'status' => 'REJECTED',
            ],
            [
                'user_id' => $nora->id,
                'post_id' => $bella->id,
                'message' => 'I would love to adopt Bella. She seems like a perfect fit for my home.',
                'status' => 'APPROVED',
            ],
            [
                'user_id' => $faisal->id,
                'post_id' => $bella->id,
                'message' => 'I am interested in Bella and would like to know more about the adoption process.',
                'status' => 'CANCELLED',
            ],
        ];

        foreach ($requests as $request) {
            AdoptionRequest::create($request);
        }
    }
}
