<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
{
    $this->call([
        CitySeeder::class,
        BreedSeeder::class,
        UserSeeder::class,
        PostSeeder::class,
        LikeSeeder::class,
        CommentSeeder::class,
        AdoptionRequestSeeder::class,
        RescueRequestSeeder::class,
        NotificationSeeder::class,
    ]);
}
}
