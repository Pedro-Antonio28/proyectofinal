<?php

namespace App\Services;

use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Alimento;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DietaService
{
    public function generarDietaSemanal($user)
    {
        $semana = Carbon::now()->weekOfYear;

        // ✅ Buscar si ya existe una dieta para esta semana
        $dieta = Dieta::firstOrCreate([
            'user_id' => $user->id,
            'semana' => $semana
        ]);

        // ✅ Si ya tiene alimentos, no la volvemos a generar
        if ($dieta->alimentos()->exists() && !empty($dieta->dieta)) {
            return $dieta;
        }


        // 🔥 Definir estructura de comidas
        $comidas = ['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'];
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        // 🔹 Obtener alimentos seleccionados por el usuario agrupados por categoría
        $alimentosDisponibles = $user->alimentos()->get()->groupBy(function($item) {
            return strtolower($item->categoria);
        });


        $dietaJson = []; // 📌 Para almacenar un resumen estructurado de la dieta

        foreach ($diasSemana as $dia) {
            $alimentosUsados = [];
            $dietaJson[$dia] = [];

            foreach ($comidas as $tipoComida) {
                $categorias = $this->categoriasParaComida($tipoComida);
                $alimentosSeleccionados = [];

                foreach ($categorias as $categoria) {
                    if (isset($alimentosDisponibles[$categoria])) {
                        $opcionesDisponibles = $alimentosDisponibles[$categoria]->whereNotIn('id', $alimentosUsados);

                        if ($opcionesDisponibles->isNotEmpty()) {
                            $alimento = $opcionesDisponibles->random();
                            $alimentosUsados[] = $alimento->id;

                            // 🔹 Ajustar cantidad según la comida
                            $cantidad = $this->determinarCantidad($tipoComida);
                            $calorias = ($alimento->calorias * $cantidad) / 100;

                            // 📌 Guardar alimento en `DietaAlimento`
                            DietaAlimento::create([
                                'dieta_id' => $dieta->id,
                                'alimento_id' => $alimento->id,
                                'dia' => $dia,
                                'tipo_comida' => $tipoComida,
                                'cantidad' => $cantidad,
                                'consumido' => false
                            ]);

                            // 📌 Guardar en JSON para la columna `dieta`
                            $alimentosSeleccionados[] = [
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

                $dietaJson[$dia][$tipoComida] = $alimentosSeleccionados;
            }
        }


        // ✅ Guardar el resumen en formato JSON en la tabla `dietas`
        $dieta->update(['dieta' => json_encode($dietaJson)]);

        return $dieta;
    }

    private function categoriasParaComida($tipoComida)
    {
        return match ($tipoComida) {
            'Desayuno' => ['carbohidratos', 'proteinas'],
            'Almuerzo' => ['frutas', 'proteinas'],
            'Comida' => ['proteinas', 'carbohidratos', 'verduras'],
            'Merienda' => ['proteinas', 'frutas'],
            'Cena' => ['proteinas', 'verduras'],
            default => ['proteinas'],
        };
    }

    private function determinarCantidad($tipoComida)
    {
        return match ($tipoComida) {
            'Desayuno' => rand(100, 250),
            'Almuerzo' => rand(50, 150),
            'Comida' => rand(150, 300),
            'Merienda' => rand(50, 150),
            'Cena' => rand(120, 220),
            default => 100,
        };
    }
}
