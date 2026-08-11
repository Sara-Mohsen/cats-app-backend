<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    const UPDATED_AT = null;

    // المستخدم الذي وضع اللايك
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // المنشور الذي عليه اللايك
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
