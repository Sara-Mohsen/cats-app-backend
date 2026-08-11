<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\City;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $sara = User::where('username', 'sara')->first();
        $ahmed = User::where('username', 'ahmed')->first();
        $nora = User::where('username', 'nora')->first();
        $faisal = User::where('username', 'faisal')->first();

        $riyadh = City::where('name', 'Riyadh')->first();
        $jeddah = City::where('name', 'Jeddah')->first();
        $dammam = City::where('name', 'Dammam')->first();
        $makkah = City::where('name', 'Makkah')->first();

        $persian = Breed::where('name', 'Persian')->first();
        $siamese = Breed::where('name', 'Siamese')->first();
        $british = Breed::where('name', 'British Shorthair')->first();
        $ragdoll = Breed::where('name', 'Ragdoll')->first();
        $domestic = Breed::where('name', 'Domestic Shorthair')->first();

        Post::create([
            'user_id' => $sara->id,
            'post_type' => 'NORMAL',
            'name' => 'Luna',
            'age_years' => 2,
            'gender' => 'FEMALE',
            'breed_id' => $persian->id,
            'city_id' => $riyadh->id,
            'image_url' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba',
            'personality_description' => 'Friendly and playful. She loves attention and enjoys playing with toys.',
            'is_neutered' => true,
            'is_vaccinated' => true,
            'status' => 'ACTIVE',
        ]);

        Post::create([
            'user_id' => $ahmed->id,
            'post_type' => 'NORMAL',
            'name' => 'Milo',
            'age_years' => 3,
            'gender' => 'MALE',
            'breed_id' => $british->id,
            'city_id' => $jeddah->id,
            'image_url' => 'https://images.unsplash.com/photo-1574158622682-e40e69881006',
            'personality_description' => 'Calm and affectionate. He prefers quiet environments and loves sleeping.',
            'is_neutered' => true,
            'is_vaccinated' => true,
            'status' => 'ACTIVE',
        ]);

        Post::create([
            'user_id' => $nora->id,
            'post_type' => 'ADOPTION',
            'name' => 'Oliver',
            'age_years' => 1,
            'gender' => 'MALE',
            'breed_id' => $ragdoll->id,
            'city_id' => $riyadh->id,
            'image_url' => 'https://images.unsplash.com/photo-1533746661557-7b3e7c6f6d43',
            'personality_description' => 'Very gentle and social. He gets along well with people and other cats.',
            'is_neutered' => false,
            'is_vaccinated' => true,
            'status' => 'ACTIVE',
        ]);

        Post::create([
            'user_id' => $faisal->id,
            'post_type' => 'ADOPTION',
            'name' => 'Bella',
            'age_years' => 2,
            'gender' => 'FEMALE',
            'breed_id' => $siamese->id,
            'city_id' => $dammam->id,
            'image_url' => 'https://images.unsplash.com/photo-1518791841217-8f162f1e1131',
            'personality_description' => 'Playful and energetic. She loves interactive toys and being around people.',
            'is_neutered' => true,
            'is_vaccinated' => true,
            'status' => 'ACTIVE',
        ]);

        Post::create([
            'user_id' => $sara->id,
            'post_type' => 'RESCUE',
            'name' => null,
            'age_years' => null,
            'gender' => 'UNKNOWN',
            'breed_id' => $domestic->id,
            'city_id' => $makkah->id,
            'image_url' => 'https://images.unsplash.com/photo-1552933529-e359b2477252',
            'personality_description' => 'The cat appears calm but is currently in need of medical attention.',
            'is_neutered' => null,
            'is_vaccinated' => null,
            'urgency_level' => 'URGENT',
            'condition' => 'Injured',
            'injury_description' => 'The cat has an injured leg and needs veterinary care as soon as possible.',
            'contact_number' => '0500000001',
            'status' => 'ACTIVE',
        ]);

        Post::create([
            'user_id' => $ahmed->id,
            'post_type' => 'RESCUE',
            'name' => null,
            'age_years' => null,
            'gender' => 'FEMALE',
            'breed_id' => null,
            'city_id' => $jeddah->id,
            'image_url' => 'https://images.unsplash.com/photo-1543852786-1cf6624b9987',
            'personality_description' => 'A friendly stray cat that needs temporary shelter and basic care.',
            'is_neutered' => null,
            'is_vaccinated' => null,
            'urgency_level' => 'MEDIUM',
            'condition' => 'Needs Care',
            'injury_description' => null,
            'contact_number' => '0500000002',
            'status' => 'ACTIVE',
        ]);
    }
}
