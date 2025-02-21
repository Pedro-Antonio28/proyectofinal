<?php

namespace App\Services;

use App\Models\User;
use App\Models\Dieta;
use Carbon\Carbon;

class DietaService
{
    public function generarDietaSemanal(User $user): array
    {
        $numeroSemana = Carbon::now()->weekOfYear;
        $dietaGuardada = Dieta::where('user_id', $user->id)->where('semana', $numeroSemana)->first();

        if ($dietaGuardada) {
            return json_decode($dietaGuardada->dieta, true);
        }

        $alimentosSeleccionados = $user->alimentos()->get();
        if ($alimentosSeleccionados->isEmpty()) {
            return [];
        }

        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $comidasDelDia = ['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'];
        $dieta = [];

        foreach ($diasSemana as $dia) {
            $dieta[$dia] = []; // 🔥 Asegurar que cada día tiene categorías vacías

            foreach ($comidasDelDia as $tipoComida) {
                $totalCalorias = 0;
                $comidas = [];

                while ($totalCalorias < ($user->calorias_necesarias * 0.95) / count($comidasDelDia)) {
                    $alimento = $alimentosSeleccionados->random();
                    $cantidad = rand(50, 150);

                    $calorias = ($alimento->calorias / 100) * $cantidad;
                    $proteinas = ($alimento->proteinas / 100) * $cantidad;
                    $carbohidratos = ($alimento->carbohidratos / 100) * $cantidad;
                    $grasas = ($alimento->grasas / 100) * $cantidad;

                    $comidas[] = [
                        'nombre' => $alimento->nombre,
                        'cantidad' => $cantidad,
                        'calorias' => round($calorias),
                        'proteinas' => round($proteinas),
                        'carbohidratos' => round($carbohidratos),
                        'grasas' => round($grasas),
                    ];

                    $totalCalorias += $calorias;
                }

                $dieta[$dia][$tipoComida] = $comidas; // 🔥 Asigna las comidas a la categoría correcta
            }
        }

        // 📌 Guarda la nueva dieta en la base de datos con la estructura correcta
        Dieta::updateOrCreate(
            ['user_id' => $user->id, 'semana' => $numeroSemana],
            ['dieta' => json_encode($dieta)]
        );

        return $dieta;
    }
}


