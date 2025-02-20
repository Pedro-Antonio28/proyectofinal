<?php

namespace App\Services;

use App\Models\User;
use App\Models\Dieta;
use Carbon\Carbon;

class DietaService
{
    public function generarDietaSemanal(User $user): array
    {
        $numeroSemana = Carbon::now()->weekOfYear; // Obtener la semana actual del año
        $dietaGuardada = Dieta::where('user_id', $user->id)->where('semana', $numeroSemana)->first();

        // 📌 Si ya existe la dieta para esta semana, la devolvemos
        if ($dietaGuardada) {
            return json_decode($dietaGuardada->dieta, true);
        }

        // 📌 Si no existe, generamos una nueva dieta
        $alimentosSeleccionados = $user->alimentos()->get();

        if ($alimentosSeleccionados->isEmpty()) {
            return []; // Si el usuario no ha seleccionado alimentos, devolvemos vacío
        }

        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $dieta = [];

        foreach ($diasSemana as $dia) {
            $totalCalorias = 0;
            $comidas = [];

            while ($totalCalorias < $user->calorias_necesarias * 0.95) {
                $alimento = $alimentosSeleccionados->random();
                $cantidad = rand(100, 200); // Cantidad en gramos

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

            $dieta[$dia] = [
                'calorias' => round($totalCalorias),
                'comidas' => $comidas
            ];
        }

        // 📌 Guardamos la nueva dieta en la base de datos para la semana actual
        Dieta::create([
            'user_id' => $user->id,
            'semana' => $numeroSemana,
            'dieta' => json_encode($dieta),
        ]);

        return $dieta;
    }
}
