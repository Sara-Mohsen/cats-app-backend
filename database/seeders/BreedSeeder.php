<?php

namespace Database\Seeders;

use App\Models\Breed;
use Illuminate\Database\Seeder;

class BreedSeeder extends Seeder
{
    public function run(): void
    {
        $breeds = [
            [
                'name' => 'Persian',
                'description' => 'Long-haired, calm, and affectionate cats.',
            ],
            [
                'name' => 'Siamese',
                'description' => 'Social, vocal, and intelligent cats.',
            ],
            [
                'name' => 'British Shorthair',
                'description' => 'Calm, friendly, and easygoing cats.',
            ],
            [
                'name' => 'Maine Coon',
                'description' => 'Large, gentle, and friendly cats.',
            ],
            [
                'name' => 'Ragdoll',
                'description' => 'Gentle, affectionate, and relaxed cats.',
            ],
            [
                'name' => 'Scottish Fold',
                'description' => 'Sweet and affectionate cats known for folded ears.',
            ],
            [
                'name' => 'Domestic Shorthair',
                'description' => 'Common short-haired mixed-breed cats.',
            ],
        ];

        foreach ($breeds as $breed) {
            Breed::create($breed);
        }
    }
}
