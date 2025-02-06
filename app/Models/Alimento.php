<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'imagen',
        'calorias',
        'proteinas',
        'carbohidratos',
        'grasas',
    ];


    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'user_alimentos');
    }

}

