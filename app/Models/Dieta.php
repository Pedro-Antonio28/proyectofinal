<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dieta extends Model
{
use HasFactory;

protected $fillable = ['user_id', 'semana', 'dieta'];

// Relación con el usuario
public function user()
{
return $this->belongsTo(User::class);
}
}
