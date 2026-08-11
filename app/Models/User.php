<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password',
        'phone',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthPassword()
{
    return $this->password;
}

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // منشورات المستخدم
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // المنشورات التي أعجب بها المستخدم
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // تعليقات المستخدم
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // طلبات التبني التي أرسلها المستخدم
    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    // طلبات الإنقاذ التي أرسلها المستخدم
    public function rescueRequests()
    {
        return $this->hasMany(RescueRequest::class);
    }

    // الإشعارات التي يستلمها المستخدم
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // الإشعارات التي تسبب بها المستخدم
    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'sender_id');
    }
}
