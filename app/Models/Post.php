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
        'urgency_level',
        'condition',
        'injury_description',
        'contact_number',
        'status',
    ];

    protected $casts = [
        'is_neutered' => 'boolean',
        'is_vaccinated' => 'boolean',
    ];

    // صاحب المنشور
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // فصيلة القطة
    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    // مدينة المنشور
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // الإعجابات
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // التعليقات
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // طلبات التبني
    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    // طلبات الإنقاذ
    public function rescueRequests()
    {
        return $this->hasMany(RescueRequest::class);
    }

    // الإشعارات المرتبطة بالمنشور
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
