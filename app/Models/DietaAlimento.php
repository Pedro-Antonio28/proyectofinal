<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietaAlimento extends Model
{
    use HasFactory;

    protected $table = 'dieta_alimentos';
    protected $fillable = ['dieta_id', 'alimento_id', 'dia', 'tipo_comida', 'cantidad', 'consumido'];

    public function alimento()
    {
        return $this->belongsTo(Alimento::class);
    }

    public function dieta()
    {
        return $this->belongsTo(Dieta::class);
    }

    public function scopeConsumidos($query, $dietaId, $dia)
    {
        return $query->where('dieta_id', $dietaId)
            ->where('dia', $dia)
            ->where('consumido', true);
    }

    public function scopeDelDia($query, $dietaId, $dia)
    {
        return $query->where('dieta_id', $dietaId)
            ->where('dia', $dia)
            ->with('alimento');
    }

}
