<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\RescueRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class RescueRequestSeeder extends Seeder
{
    public function run(): void
    {
        $sara = User::where('username', 'sara')->first();
        $nora = User::where('username', 'nora')->first();
        $faisal = User::where('username', 'faisal')->first();
        $ahmed = User::where('username', 'ahmed')->first();

        $injuredCat = Post::where('post_type', 'RESCUE')
            ->where('is_injured', true)
            ->first();

        $healthyCat = Post::where('post_type', 'RESCUE')
            ->where('is_injured', false)
            ->first();

        $requests = [
            [
                'user_id' => $nora->id,
                'post_id' => $injuredCat->id,
                'status' => 'PENDING',
            ],
            [
                'user_id' => $faisal->id,
                'post_id' => $healthyCat->id,
                'status' => 'ACCEPTED',
            ],
            [
                'user_id' => $sara->id,
                'post_id' => $injuredCat->id,
                'status' => 'ACCEPTED',
            ],
            [
                'user_id' => $ahmed->id,
                'post_id' => $healthyCat->id,
                'status' => 'REJECTED',
            ],
        ];

        foreach ($requests as $request) {
            RescueRequest::create($request);
        }
    }
}
