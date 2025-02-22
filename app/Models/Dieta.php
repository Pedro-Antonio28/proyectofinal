<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dieta extends Model
{
    use HasFactory;

    protected $table = 'dietas';
    protected $fillable = ['user_id', 'semana', 'dieta'];

    public function alimentos()
    {
        return $this->hasMany(DietaAlimento::class);
    }


}
