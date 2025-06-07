<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Scopes\OrderByLikesScope;

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
            ->withPivot(['added_at', 'custom_notes', 'es_favorito'])
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



    protected static function booted() : void
    {
        static::addGlobalScope(new OrderByLikesScope);

        static::creating(function ($post) {
            $post->slug = Str::slug($post->title);
        });
    }

    public function scopeConIngredientesSimilares($query, string $nombre)
    {
        return $query->withCount('likes')
            ->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(ingredients, '$'))) LIKE ?", ['%' . strtolower($nombre) . '%']);
    }
}
