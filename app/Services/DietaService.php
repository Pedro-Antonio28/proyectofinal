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

        // Buscar o crear la dieta
        $dieta = Dieta::firstOrCreate([
            'user_id' => $user->id,
            'semana' => $semana
        ]);

        // Eliminar todos los registros anteriores para que se regenere la dieta completa
        DietaAlimento::where('dieta_id', $dieta->id)->delete();

        // Generar la dieta para todos los días y realizar los ajustes de macros
        $this->generarDietaBase($dieta, $user);
        $this->ajustarMacrosDia($dieta, $user);

        return $dieta;
    }


    /**
     * Genera la dieta base para toda la semana (la parte que ya tienes).
     * He extraído este trozo en un método aparte para que sea más limpio.
     */
    /**
     * Genera la dieta base para toda la semana.
     * - Distribuye los macronutrientes por comida (según un repartoMacros).
     * - Selecciona alimentos de las categorías adecuadas.
     * - Ajusta la cantidad según el macro crítico.
     * - Evita sobrepasos muy grandes con una comprobación extra.
     * - Finalmente, actualiza la columna 'dieta' de la tabla dietas (en JSON).
     */
    private function generarDietaBase(Dieta $dieta, $user)
    {
        $comidas = ['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'];
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        $alimentosDisponibles = $user->alimentos()->get()->groupBy(function ($item) {
            return strtolower($item->categoria);
        });

        $caloriasDiarias      = $user->calorias_necesarias;
        $proteinasDiarias     = $user->proteinas;
        $carbohidratosDiarias = $user->carbohidratos;
        $grasasDiarias        = $user->grasas;

        $umbralInferior = 0.93;
        $umbralSuperior = 1.07;

        $dietaJson = [];

        $maxAlimentosPorComida = [
            'Desayuno' => 3,
            'Almuerzo' => 2,
            'Comida'   => 5,
            'Merienda' => 2,
            'Cena'     => 4
        ];

        $repartoMacros = [
            'Desayuno' => 0.20,
            'Almuerzo' => 0.10,
            'Comida'   => 0.40,
            'Merienda' => 0.10,
            'Cena'     => 0.20,
        ];

        $limitesPorCategoria = [
            'proteinas' => ['min' => 50, 'max' => 200],
            'carbohidratos' => ['min' => 50, 'max' => 200],
            'verduras' => ['min' => 30, 'max' => 100],
            'frutas' => ['min' => 50, 'max' => 150],
            'grasas' => ['min' => 10, 'max' => 50],
        ];

        $alimentosUsadosGlobal = [];

        foreach ($diasSemana as $dia) {
            $dietaJson[$dia] = [];
            $alimentosUsadosDia = [];

            foreach ($comidas as $tipoComida) {
                $alimentosSeleccionados = [];

                $calObj  = $caloriasDiarias      * $repartoMacros[$tipoComida];
                $protObj = $proteinasDiarias     * $repartoMacros[$tipoComida];
                $carbObj = $carbohidratosDiarias * $repartoMacros[$tipoComida];
                $grasObj = $grasasDiarias        * $repartoMacros[$tipoComida];

                $totCal  = 0;
                $totProt = 0;
                $totCarb = 0;
                $totGras = 0;

                $intentos = 0;

                $categorias = $this->categoriasParaComida($tipoComida);
                shuffle($categorias);

                while (
                    (
                        $totCal  < $calObj  * $umbralInferior ||
                        $totProt < $protObj * $umbralInferior ||
                        $totCarb < $carbObj * $umbralInferior ||
                        $totGras < $grasObj * $umbralInferior
                    )
                    && count($alimentosSeleccionados) < $maxAlimentosPorComida[$tipoComida]
                    && $intentos < 10
                ) {
                    $intentos++;

                    $mejorScore = null;
                    $mejorAlimento = null;
                    $mejorCantidad = null;

                    foreach ($categorias as $cat) {
                        if (!isset($alimentosDisponibles[$cat])) continue;

                        $usadosGlobal = $alimentosUsadosGlobal[$tipoComida] ?? [];
                        $opciones = $alimentosDisponibles[$cat]->whereNotIn('id', array_merge($usadosGlobal, $alimentosUsadosDia));

                        if ($opciones->isEmpty()) {
                            $opciones = $alimentosDisponibles[$cat]->whereNotIn('id', $alimentosUsadosDia);
                        }

                        foreach ($opciones->shuffle() as $al) {
                            $categoria = strtolower($al->categoria);
                            $min = $limitesPorCategoria[$categoria]['min'] ?? 50;
                            $max = $limitesPorCategoria[$categoria]['max'] ?? 200;

                            for ($candCant = $min; $candCant <= $max; $candCant += 10) {
                                $fCal  = $totCal  + ($al->calorias      * $candCant / 100);
                                $fProt = $totProt + ($al->proteinas     * $candCant / 100);
                                $fCarb = $totCarb + ($al->carbohidratos * $candCant / 100);
                                $fGras = $totGras + ($al->grasas        * $candCant / 100);

                                // Limita exceso por encima del umbral superior
                                if (
                                    $fCal  > $calObj  * $umbralSuperior ||
                                    $fProt > $protObj * $umbralSuperior ||
                                    $fCarb > $carbObj * $umbralSuperior ||
                                    $fGras > $grasObj * $umbralSuperior
                                ) {
                                    continue;
                                }

                                $score = 0;
                                if ($calObj  > 0) $score += pow(($fCal  - $calObj ) / $calObj, 2);
                                if ($protObj > 0) $score += pow(($fProt - $protObj) / $protObj, 2);
                                if ($carbObj > 0) $score += pow(($fCarb - $carbObj) / $carbObj, 2);
                                if ($grasObj > 0) $score += pow(($fGras - $grasObj) / $grasObj, 2);
                                $score += mt_rand(0, 100) / 10000;

                                if (is_null($mejorScore) || $score < $mejorScore) {
                                    $mejorScore    = $score;
                                    $mejorAlimento = $al;
                                    $mejorCantidad = $candCant;
                                }
                            }
                        }
                    }

                    if (!$mejorAlimento) break;

                    $alimentosUsadosDia[] = $mejorAlimento->id;
                    $alimentosUsadosGlobal[$tipoComida][] = $mejorAlimento->id;

                    DietaAlimento::create([
                        'dieta_id'    => $dieta->id,
                        'alimento_id' => $mejorAlimento->id,
                        'dia'         => $dia,
                        'tipo_comida' => $tipoComida,
                        'cantidad'    => $mejorCantidad,
                        'consumido'   => false
                    ]);

                    $totCal  += ($mejorAlimento->calorias      * $mejorCantidad / 100);
                    $totProt += ($mejorAlimento->proteinas     * $mejorCantidad / 100);
                    $totCarb += ($mejorAlimento->carbohidratos * $mejorCantidad / 100);
                    $totGras += ($mejorAlimento->grasas        * $mejorCantidad / 100);

                    $alimentosSeleccionados[] = [
                        'alimento_id'   => $mejorAlimento->id,
                        'nombre'        => $mejorAlimento->nombre,
                        'cantidad'      => $mejorCantidad,
                        'calorias'      => round(($mejorAlimento->calorias * $mejorCantidad) / 100),
                        'proteinas'     => round(($mejorAlimento->proteinas * $mejorCantidad) / 100, 1),
                        'carbohidratos' => round(($mejorAlimento->carbohidratos * $mejorCantidad) / 100, 1),
                        'grasas'        => round(($mejorAlimento->grasas * $mejorCantidad) / 100, 1),
                    ];
                }

                $dietaJson[$dia][$tipoComida] = $alimentosSeleccionados;
            }
        }

        $dieta->update(['dieta' => json_encode($dietaJson)]);
    }




    /**
     * Realiza un post-procesado diario: revisa si estamos muy por debajo en algún macro
     * y trata de corregirlo añadiendo uno o dos alimentos extra (o ajustando cantidades).
     */
    private function ajustarMacrosDia(Dieta $dieta, $user)
    {
        // Cargamos necesidades diarias
        $caloriasDiarias      = $user->calorias_necesarias;
        $proteinasDiarias     = $user->proteinas;
        $carbohidratosDiarias = $user->carbohidratos;
        $grasasDiarias        = $user->grasas;

        // Alimentos disponibles sin filtrar
        $alimentosDisponibles = $user->alimentos()->get();

        // Rango aceptable: 93% a 107%
        $umbralInferior = 0.93;
        $umbralSuperior = 1.07;

        $limitesPorCategoria = [
            'proteinas' => ['min' => 50, 'max' => 200],
            'carbohidratos' => ['min' => 50, 'max' => 200],
            'verduras' => ['min' => 30, 'max' => 100],
            'frutas' => ['min' => 50, 'max' => 150],
            'grasas' => ['min' => 10, 'max' => 50],
        ];

        $dietaJson = json_decode($dieta->dieta, true);

        foreach ($dietaJson as $dia => $comidasDia) {
            $totalCalorias = 0;
            $totalProteinas = 0;
            $totalCarbohidratos = 0;
            $totalGrasas = 0;

            $dietaAlimentos = DietaAlimento::where('dieta_id', $dieta->id)
                ->where('dia', $dia)
                ->get();

            foreach ($dietaAlimentos as $da) {
                $alimento = $da->alimento;
                $cantidad = $da->cantidad;

                $totalCalorias      += ($alimento->calorias      * $cantidad) / 100;
                $totalProteinas     += ($alimento->proteinas     * $cantidad) / 100;
                $totalCarbohidratos += ($alimento->carbohidratos * $cantidad) / 100;
                $totalGrasas        += ($alimento->grasas        * $cantidad) / 100;
            }

            $necesitaMasCal   = ($totalCalorias      < $caloriasDiarias      * $umbralInferior);
            $necesitaMasProt  = ($totalProteinas     < $proteinasDiarias     * $umbralInferior);
            $necesitaMasCarb  = ($totalCarbohidratos < $carbohidratosDiarias * $umbralInferior);
            $necesitaMasGrasa = ($totalGrasas        < $grasasDiarias        * $umbralInferior);

            if (!($necesitaMasCal || $necesitaMasProt || $necesitaMasCarb || $necesitaMasGrasa)) {
                continue;
            }

            if (!isset($dietaJson[$dia]['Snack'])) {
                $dietaJson[$dia]['Snack'] = [];
            }

            for ($i = 0; $i < 2; $i++) {
                $faltanteCal   = max(0, $caloriasDiarias      - $totalCalorias);
                $faltanteProt  = max(0, $proteinasDiarias     - $totalProteinas);
                $faltanteCarb  = max(0, $carbohidratosDiarias - $totalCarbohidratos);
                $faltanteGrasa = max(0, $grasasDiarias        - $totalGrasas);

                $ratioCal   = ($caloriasDiarias      > 0) ? $faltanteCal   / $caloriasDiarias      : 0;
                $ratioProt  = ($proteinasDiarias     > 0) ? $faltanteProt  / $proteinasDiarias     : 0;
                $ratioCarb  = ($carbohidratosDiarias > 0) ? $faltanteCarb  / $carbohidratosDiarias : 0;
                $ratioGrasa = ($grasasDiarias        > 0) ? $faltanteGrasa / $grasasDiarias        : 0;

                $maxRatio = max($ratioCal, $ratioProt, $ratioCarb, $ratioGrasa);
                if ($maxRatio < (1 - $umbralInferior)) {
                    break;
                }

                $macroCritico = match (true) {
                    $maxRatio === $ratioCal => 'calorias',
                    $maxRatio === $ratioProt => 'proteinas',
                    $maxRatio === $ratioCarb => 'carbohidratos',
                    default => 'grasas',
                };

                $alimentoExtra = $this->seleccionarAlimentoPorMacro($alimentosDisponibles, $macroCritico);
                if (!$alimentoExtra) break;

                $categoria = strtolower($alimentoExtra->categoria);
                $min = $limitesPorCategoria[$categoria]['min'] ?? 50;
                $max = $limitesPorCategoria[$categoria]['max'] ?? 200;

                $cantidad = 100;
                if ($macroCritico === 'proteinas' && $alimentoExtra->proteinas > 0) {
                    $cantidad = round($faltanteProt * 100 / $alimentoExtra->proteinas);
                } elseif ($macroCritico === 'carbohidratos' && $alimentoExtra->carbohidratos > 0) {
                    $cantidad = round($faltanteCarb * 100 / $alimentoExtra->carbohidratos);
                } elseif ($macroCritico === 'grasas' && $alimentoExtra->grasas > 0) {
                    $cantidad = round($faltanteGrasa * 100 / $alimentoExtra->grasas);
                } elseif ($macroCritico === 'calorias' && $alimentoExtra->calorias > 0) {
                    $cantidad = round($faltanteCal * 100 / $alimentoExtra->calorias);
                }

                $cantidad = min($max, max($min, $cantidad));

                DietaAlimento::create([
                    'dieta_id'    => $dieta->id,
                    'alimento_id' => $alimentoExtra->id,
                    'dia'         => $dia,
                    'tipo_comida' => 'Snack',
                    'cantidad'    => $cantidad,
                    'consumido'   => false
                ]);

                $dietaJson[$dia]['Snack'][] = [
                    'nombre'        => $alimentoExtra->nombre,
                    'cantidad'      => $cantidad,
                    'calorias'      => round(($alimentoExtra->calorias      * $cantidad) / 100),
                    'proteinas'     => round(($alimentoExtra->proteinas     * $cantidad) / 100, 1),
                    'carbohidratos' => round(($alimentoExtra->carbohidratos * $cantidad) / 100, 1),
                    'grasas'        => round(($alimentoExtra->grasas        * $cantidad) / 100, 1),
                ];

                // Actualizar los totales
                $totalCalorias      += ($alimentoExtra->calorias      * $cantidad) / 100;
                $totalProteinas     += ($alimentoExtra->proteinas     * $cantidad) / 100;
                $totalCarbohidratos += ($alimentoExtra->carbohidratos * $cantidad) / 100;
                $totalGrasas        += ($alimentoExtra->grasas        * $cantidad) / 100;

                // Si ya pasamos del 107% en todos los macros, cortamos
                if (
                    $totalCalorias      > $caloriasDiarias      * $umbralSuperior &&
                    $totalProteinas     > $proteinasDiarias     * $umbralSuperior &&
                    $totalCarbohidratos > $carbohidratosDiarias * $umbralSuperior &&
                    $totalGrasas        > $grasasDiarias        * $umbralSuperior
                ) {
                    break;
                }
            }
        }

        $dieta->update(['dieta' => json_encode($dietaJson)]);
    }


    /**
     * Ejemplo de función para seleccionar un alimento “rico” en un macro concreto.
     * Puedes definir tus propios criterios.
     */
    private function seleccionarAlimentoPorMacro($alimentos, $macroCritico)
    {
        // Por ejemplo, si $macroCritico = 'carbohidratos', elegimos aquel que tenga
        // la mayor proporción de carbos (carbohidratos * 4 / caloríasTotales > X).
        // Ajusta la lógica a tu gusto.

        // Filtramos para quedarnos con los que aportan algo de ese macro
        $filtrados = $alimentos->filter(function($al) use ($macroCritico) {
            switch($macroCritico) {
                case 'proteinas':
                    return $al->proteinas > 5; // un mínimo arbitrario
                case 'carbohidratos':
                    return $al->carbohidratos > 5;
                case 'grasas':
                    return $al->grasas > 2;
                case 'calorias':
                default:
                    return $al->calorias > 50; // “rico” en calorías
            }
        });

        if ($filtrados->isEmpty()) {
            return null;
        }

        // Escoger uno al azar de los filtrados (o podrías escoger el que tenga más de ese macro)
        return $filtrados->random();
    }

    /**
     * Calcula la cantidad que vamos a añadir en el Snack.
     *
     * En lugar de un número fijo, se hace un cálculo basado en el déficit
     * y en el macro crítico. Intentamos no pasarnos demasiado de los otros macros.
     */
    private function calcularCantidadExtra($alimento, $faltanteCal, $faltanteProt, $faltanteCarb, $faltanteGrasa, $macroCritico)
    {
        // Por simplicidad, supongamos un rango 50-200 g
        $cantidad = 100;

        // Si el macro crítico es proteinas:
        if ($macroCritico === 'proteinas' && $alimento->proteinas > 0) {
            $cantidad = round($faltanteProt * 100 / $alimento->proteinas);
        } elseif ($macroCritico === 'carbohidratos' && $alimento->carbohidratos > 0) {
            $cantidad = round($faltanteCarb * 100 / $alimento->carbohidratos);
        } elseif ($macroCritico === 'grasas' && $alimento->grasas > 0) {
            $cantidad = round($faltanteGrasa * 100 / $alimento->grasas);
        } elseif ($macroCritico === 'calorias' && $alimento->calorias > 0) {
            // Dividimos el faltante de calorías entre calorías/100
            $cantidad = round($faltanteCal * 100 / $alimento->calorias);
        }

        // Límite 50-200
        $cantidad = min(200, max(50, $cantidad));
        return $cantidad;
    }




    private function categoriasParaComida($tipoComida)
    {
        return match ($tipoComida) {
            'Desayuno' => ['carbohidratos', 'proteinas', 'grasas'],
            'Almuerzo' => ['frutas', 'proteinas', 'grasas'],
            'Comida'   => ['proteinas', 'carbohidratos', 'verduras', 'grasas'],
            'Merienda' => ['proteinas', 'frutas'],
            'Cena'     => ['proteinas', 'verduras', 'grasas'],
            default    => ['proteinas'],
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
