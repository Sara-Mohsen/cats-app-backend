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
        $ahmed = User::where('username', 'ahmed')->first();
        $nora = User::where('username', 'nora')->first();
        $faisal = User::where('username', 'faisal')->first();

        $injuredCat = Post::where('post_type', 'RESCUE')
            ->where('urgency_level', 'URGENT')
            ->first();

        $careCat = Post::where('post_type', 'RESCUE')
            ->where('urgency_level', 'MEDIUM')
            ->first();

        $requests = [
            [
                'user_id' => $nora->id,
                'post_id' => $injuredCat->id,
                'message' => 'I can help take the cat to a veterinary clinic and cover the initial treatment.',
                'status' => 'PENDING',
            ],
            [
                'user_id' => $faisal->id,
                'post_id' => $injuredCat->id,
                'message' => 'I can provide transportation and help arrange emergency veterinary care.',
                'status' => 'ACCEPTED',
            ],
            [
                'user_id' => $sara->id,
                'post_id' => $careCat->id,
                'message' => 'I can temporarily foster the cat and provide food and basic care.',
                'status' => 'ACCEPTED',
            ],
            [
                'user_id' => $faisal->id,
                'post_id' => $careCat->id,
                'message' => 'I would be available to help with the cat if needed.',
                'status' => 'REJECTED',
            ],
        ];

        foreach ($requests as $request) {
            RescueRequest::create($request);
        }
    }
}
