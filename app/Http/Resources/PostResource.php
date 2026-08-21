<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isRescue = $this->post_type === 'RESCUE';
        $isAdoption = $this->post_type === 'ADOPTION';

        return [
            'id' => $this->id,

            'type' => $this->post_type,

            'case_number' => $this->when(
                $isRescue,
                'Rescue #' . str_pad($this->id, 2, '0', STR_PAD_LEFT)
            ),

            'image' => $this->image_url
                ? asset('storage/' . $this->image_url)
                : null,

            'created_at' => $this->created_at,


            'name' => $this->when(
                !$isRescue,
                $this->name
            ),

            'age' => $this->when(
                !$isRescue,
                $this->age_years
            ),

            'gender' => $this->when(
                !$isRescue,
                $this->gender
            ),

            'personality' => $this->when(
                !$isRescue,
                $this->personality_description
            ),

            'is_neutered' => $this->when(
                !$isRescue,
                $this->is_neutered
            ),

            'is_vaccinated' => $this->when(
                !$isRescue,
                $this->is_vaccinated
            ),

            'contact_number' => $this->when(
                $isAdoption || $isRescue,
                $this->contact_number
            ),

            'status' => $this->when(
                $isAdoption || $isRescue,
                $this->status
            ),


            'is_injured' => $this->when(
                $isRescue,
                $this->is_injured
            ),

            'injury_description' => $this->when(
                $isRescue && $this->is_injured,
                $this->injury_description
            ),


            'breed' => $this->when(
                !$isRescue,
                $this->breed ? [
                    'id' => $this->breed->id,
                    'name' => $this->breed->name,
                ] : null
            ),

            'city' => [
                'id' => $this->city->id,
                'name' => $this->city->name,
            ],

            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'avatar_url' => $this->user->avatar_url,
            ],
        ];
    }
}
