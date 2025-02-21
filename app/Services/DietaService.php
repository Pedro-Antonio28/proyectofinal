<?php

namespace App\Services;

use App\Models\Alimento;
use Illuminate\Support\Facades\Auth;

class DietaService
{
    public function generarDietaSemanal($user)
    {
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $categorias = ['proteinas', 'carbohidratos', 'grasas', 'frutas', 'verduras'];

        $dieta = [];

        foreach ($diasSemana as $dia) {
            $dieta[$dia] = $this->generarDietaDiaria($categorias);
        }

        return $dieta;
    }

    private function generarDietaDiaria($categorias)
    {
        // 🔹 Obtener todos los alimentos seleccionados por el usuario
        $user = Auth::user();
        $alimentosDisponibles = $user->alimentos()->get()->groupBy('categoria');

        // 🔹 Definir las comidas del día con sus pesos calóricos
        $comidas = [
            'Desayuno' => 0.25,  // 🔥 25% del total diario
            'Almuerzo' => 0.075, // 🥗 7.5% del total diario
            'Comida' => 0.40,    // 🍽️ 40% del total diario (Más grande)
            'Merienda' => 0.075, // ☕ 7.5% del total diario
            'Cena' => 0.20       // 🌙 20% del total diario
        ];

        $dietaDiaria = [];
        $alimentosUsados = [];

        foreach ($comidas as $comida => $proporcion) {
            $dietaDiaria[$comida] = [];
            $caloriasMeta = $user->calorias_necesarias * $proporcion; // 🔹 Ajustamos calorías según la comida
            $caloriasAcumuladas = 0;

            foreach ($categorias as $categoria) {
                if (isset($alimentosDisponibles[$categoria])) {
                    // 🔹 Filtrar alimentos que no han sido usados aún en el día
                    $opcionesDisponibles = $alimentosDisponibles[$categoria]->whereNotIn('id', $alimentosUsados);

                    if ($opcionesDisponibles->isNotEmpty()) {
                        // 🔹 Seleccionar un alimento aleatorio dentro de la categoría
                        $alimento = $opcionesDisponibles->random();
                        $alimentosUsados[] = $alimento->id; // Marcarlo como usado

                        // 🔹 Ajustar cantidad para cumplir el objetivo de calorías
                        $cantidad = rand(80, 250); // 📌 Las comidas grandes tienen más cantidad

                        // Si la comida es ligera (almuerzo o merienda), reducir la cantidad
                        if (in_array($comida, ['Almuerzo', 'Merienda'])) {
                            $cantidad = rand(50, 150);
                        }

                        // Calcular calorías según la cantidad
                        $calorias = ($alimento->calorias * $cantidad) / 100;

                        // Evitar que se pase demasiado del objetivo calórico de la comida
                        if ($caloriasAcumuladas + $calorias <= $caloriasMeta) {
                            $caloriasAcumuladas += $calorias;

                            // 🔹 Agregar alimento a la comida
                            $dietaDiaria[$comida][] = [
                                'nombre' => $alimento->nombre,
                                'cantidad' => $cantidad,
                                'calorias' => round($calorias),
                                'proteinas' => round(($alimento->proteinas * $cantidad) / 100, 1),
                                'carbohidratos' => round(($alimento->carbohidratos * $cantidad) / 100, 1),
                                'grasas' => round(($alimento->grasas * $cantidad) / 100, 1),
                            ];
                        }
                    }
                }
            }
        }

        return $dietaDiaria;
    }
}
