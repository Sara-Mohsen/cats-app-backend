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

    protected $primaryKey = ['user_id', 'post_id'];
    public $incrementing = false;

    const UPDATED_AT = null;

    protected function setKeysForSaveQuery($query)
    {
        return $query
            ->where('user_id', $this->getAttribute('user_id'))
            ->where('post_id', $this->getAttribute('post_id'));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
