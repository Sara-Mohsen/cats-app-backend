<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // المدينة تحتوي على منشورات كثيرة
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
