<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'macros', 'image_path',
    ];

    protected $casts = [
        'macros' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usersWhoSavedIt()
    {
        return $this->belongsToMany(User::class, 'post_user')
            ->withPivot(['added_at', 'custom_notes'])
            ->withTimestamps();
    }
}
