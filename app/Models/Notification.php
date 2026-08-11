<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_id',
        'post_id',
        'type',
        'message',
        'is_read',
    ];

    const UPDATED_AT = null;

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // المستخدم الذي استلم الإشعار
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // المستخدم الذي تسبب في الإشعار
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // المنشور المرتبط بالإشعار
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
