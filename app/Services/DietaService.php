<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class DietaService
{
    /**
     * Genera una dieta semanal basada en los alimentos del usuario y sus necesidades.
     */
    public function generarDietaSemanal(User $user): array
    {
        $alimentosSeleccionados = $user->alimentos()->get();
        // Relación con los alimentos elegidos
        $caloriasDiarias = $user->calorias_necesarias;
        $proteinasDiarias = $user->proteinas;
        $carbohidratosDiarios = $user->carbohidratos;
        $grasasDiarias = $user->grasas;

        if (!$alimentosSeleccionados || $alimentosSeleccionados->isEmpty()) {
            return [];
        }


        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $dieta = [];

        foreach ($diasSemana as $dia) {
            $totalCalorias = 0;
            $totalProteinas = 0;
            $totalCarbohidratos = 0;
            $totalGrasas = 0;
            $comidas = [];

            while ($totalCalorias < $caloriasDiarias * 0.95) { // Evitar exceso
                $alimento = $alimentosSeleccionados->random();
                $cantidad = rand(100, 200); // En gramos

                $calorias = ($alimento->calorias / 100) * $cantidad;
                $proteinas = ($alimento->proteinas / 100) * $cantidad;
                $carbohidratos = ($alimento->carbohidratos / 100) * $cantidad;
                $grasas = ($alimento->grasas / 100) * $cantidad;

                // Agregamos la comida del día
                $comidas[] = [
                    'nombre' => $alimento->nombre,
                    'cantidad' => $cantidad,
                    'calorias' => round($calorias),
                    'proteinas' => round($proteinas),
                    'carbohidratos' => round($carbohidratos),
                    'grasas' => round($grasas),
                ];

                $totalCalorias += $calorias;
                $totalProteinas += $proteinas;
                $totalCarbohidratos += $carbohidratos;
                $totalGrasas += $grasas;
            }

            $dieta[$dia] = [
                'calorias' => round($totalCalorias),
                'proteinas' => round($totalProteinas),
                'carbohidratos' => round($totalCarbohidratos),
                'grasas' => round($totalGrasas),
                'comidas' => $comidas,
            ];
        }

        return $dieta;
    }
}
