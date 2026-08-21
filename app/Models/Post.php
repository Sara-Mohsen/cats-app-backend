<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_type',
        'name',
        'age_years',
        'gender',
        'breed_id',
        'city_id',
        'image_url',
        'personality_description',
        'is_neutered',
        'is_vaccinated',
        'is_injured',
        'injury_description',
        'contact_number',
        'status',
    ];

    protected $casts = [
        'is_neutered' => 'boolean',
        'is_vaccinated' => 'boolean',
        'is_injured' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function rescueRequests()
    {
        return $this->hasMany(RescueRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
