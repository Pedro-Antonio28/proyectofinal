<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'peso',
        'altura',
        'age',
        'gender',
        'objetivo',
        'actividad',
        'calorias_necesarias',
        'proteinas',
        'carbohidratos',
        'grasas'
    ];

    /**
     * Función para calcular las calorías necesarias del usuario.
     */
    public function calcularCalorias()
    {
        // Verificar que todos los valores existen antes de calcular
        if (!$this->peso || !$this->altura || !$this->age || !$this->gender || !$this->actividad || !$this->objetivo) {
            return null; // Evita errores si falta algún dato
        }

        // Cálculo del BMR según el género
        if ($this->gender === 'male') {
            $bmr = 88.36 + (13.4 * $this->peso) + (4.8 * $this->altura) - (5.7 * $this->age);
        } else {
            $bmr = 447.6 + (9.2 * $this->peso) + (3.1 * $this->altura) - (4.3 * $this->age);
        }

        // Multiplicar por el factor de actividad
        $factores_actividad = [
            'sedentario' => 1.2,
            'ligero' => 1.375,
            'moderado' => 1.55,
            'intenso' => 1.725,
        ];

        $factor = $factores_actividad[$this->actividad] ?? 1.2;
        $calorias_mantenimiento = $bmr * $factor;

        // Ajuste según el objetivo del usuario
        if ($this->objetivo === 'perder_peso') {
            $calorias_finales = $calorias_mantenimiento - 400; // Reducir calorías
        } elseif ($this->objetivo === 'ganar_musculo') {
            $calorias_finales = $calorias_mantenimiento + 400; // Aumentar calorías
        } else {
            $calorias_finales = $calorias_mantenimiento; // Mantener peso
        }

        return round($calorias_finales); // Devolver el número redondeado
    }


    /**
     * Guardar las calorías en la base de datos.
     */
    public function actualizarCalorias()
    {
        $calorias = $this->calcularCalorias();

        if ($calorias !== null) { // Solo guardar si el cálculo es válido
            $this->calorias_necesarias = $calorias;
            $this->save();
        }
    }




    /**
     * Guardar las calorías y macronutrientes en la base de datos.
     */
    public function actualizarCaloriasYMacros()
    {
        $this->calorias_necesarias = $this->calcularCalorias();

        // Calcular macronutrientes
        $macros = $this->calcularMacronutrientes();
        $this->proteinas = $macros['proteinas'];
        $this->grasas = $macros['grasas'];
        $this->carbohidratos = $macros['carbohidratos'];

        $this->save();
    }

    public function alimentosFavoritos()
    {
        return $this->belongsToMany(Alimento::class, 'user_alimentos');
    }

    public function alimentos()
    {
        return $this->belongsToMany(Alimento::class, 'user_alimentos');
    }

    public function dietas()
    {
        return $this->hasMany(Dieta::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function hasPermission($permission)
    {
        return $this->permissions->contains('name', $permission) ||
            $this->roles->flatMap->permissions->contains('name', $permission);
    }
}

