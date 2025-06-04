<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'macros', 'ingredients', 'image_path',
    ];

    protected $casts = [
        'macros' => 'array',
        'ingredients' => 'array',
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

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function images()
    {
        return $this->hasMany(PostImage::class);
    }



    protected static function booted()
    {
        static::creating(function ($post) {
            $post->slug = Str::slug($post->title);
        });
    }

}
