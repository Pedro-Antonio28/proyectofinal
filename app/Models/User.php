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
        'calorias_necesarias', // Nuevo campo para guardar las calorías
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

    public function calcularMacronutrientes()
    {
        if (!$this->calorias_necesarias || !$this->objetivo) {
            return null; // Evita errores si faltan datos
        }

        // Porcentajes de macronutrientes según el objetivo
        $objetivos = [
            'perder_peso' => ['proteina' => 0.30, 'grasa' => 0.25, 'carbohidratos' => 0.45],
            'mantener_peso' => ['proteina' => 0.25, 'grasa' => 0.25, 'carbohidratos' => 0.50],
            'ganar_musculo' => ['proteina' => 0.35, 'grasa' => 0.20, 'carbohidratos' => 0.45],
        ];

        $macro = $objetivos[$this->objetivo] ?? $objetivos['mantener_peso'];

        // Cálculo de gramos de macronutrientes
        $proteinas = round(($this->calorias_necesarias * $macro['proteina']) / 4);
        $grasas = round(($this->calorias_necesarias * $macro['grasa']) / 9);
        $carbohidratos = round(($this->calorias_necesarias * $macro['carbohidratos']) / 4);

        return [
            'proteinas' => $proteinas,
            'grasas' => $grasas,
            'carbohidratos' => $carbohidratos,
        ];
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

}

