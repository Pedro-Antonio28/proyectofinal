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

        // 1. Buscar o crear la dieta
        $dieta = Dieta::firstOrCreate([
            'user_id' => $user->id,
            'semana' => $semana
        ]);

        // 2. Si ya existe, no regenerar
        if ($dieta->alimentos()->exists() && !empty($dieta->dieta)) {
            return $dieta;
        }

        // 3. Generar la dieta base (misma lógica que ya tienes)
        $this->generarDietaBase($dieta, $user);

        // 4. Realizar la segunda pasada de ajuste
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
        // 1. Definir comidas y días
        $comidas = ['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'];
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        // 2. Alimentos por categoría
        $alimentosDisponibles = $user->alimentos()->get()->groupBy(function($item) {
            return strtolower($item->categoria);
        });

        // 3. Objetivos diarios
        $caloriasDiarias      = $user->calorias_necesarias;
        $proteinasDiarias     = $user->proteinas;
        $carbohidratosDiarias = $user->carbohidratos;
        $grasasDiarias        = $user->grasas;

        // 4. Estructura para el JSON final
        $dietaJson = [];

        // 5. Máximo de alimentos por comida
        $maxAlimentosPorComida = [
            'Desayuno' => 3,
            'Almuerzo' => 2,
            'Comida'   => 5,
            'Merienda' => 2,
            'Cena'     => 4
        ];

        // 6. Reparto macros
        $repartoMacros = [
            'Desayuno' => 0.20,
            'Almuerzo' => 0.10,
            'Comida'   => 0.40,
            'Merienda' => 0.10,
            'Cena'     => 0.20,
        ];

        // 7. Generar la dieta para cada día
        foreach ($diasSemana as $dia) {
            $dietaJson[$dia] = [];
            $alimentosUsados = [];

            foreach ($comidas as $tipoComida) {
                $alimentosSeleccionados = [];

                // Objetivos para esta comida
                $calObj  = $caloriasDiarias      * $repartoMacros[$tipoComida];
                $protObj = $proteinasDiarias     * $repartoMacros[$tipoComida];
                $carbObj = $carbohidratosDiarias * $repartoMacros[$tipoComida];
                $grasObj = $grasasDiarias        * $repartoMacros[$tipoComida];

                // Totales acumulados en esta comida
                $totCal  = 0;
                $totProt = 0;
                $totCarb = 0;
                $totGras = 0;

                $intentos = 0;
                $seAgregoAlimento = false;

                // Categorías permitidas en esta comida
                $categorias = $this->categoriasParaComida($tipoComida);

                // Bucle de selección: hasta llegar ~98% o quedarnos sin opciones
                while (
                    (
                        $totCal  < $calObj  * 0.98 ||
                        $totProt < $protObj * 0.98 ||
                        $totCarb < $carbObj * 0.98 ||
                        $totGras < $grasObj * 0.98
                    )
                    && count($alimentosSeleccionados) < $maxAlimentosPorComida[$tipoComida]
                    && $intentos < 10
                ) {
                    $intentos++;

                    // 7.1. Buscar la mejor combinación (alimento+cantidad) que minimice el score
                    $mejorScore = null;
                    $mejorAlimento = null;
                    $mejorCantidad = null;

                    // Recorremos las categorías
                    foreach ($categorias as $cat) {
                        if (!isset($alimentosDisponibles[$cat])) {
                            continue;
                        }
                        // Opciones que no se han usado
                        $opciones = $alimentosDisponibles[$cat]->whereNotIn('id', $alimentosUsados);

                        // Para cada alimento en esa categoría
                        foreach ($opciones as $al) {
                            // Probamos cantidades en saltos de 10g (por ejemplo) para no hacer 1 en 1
                            for ($candCant = 50; $candCant <= 200; $candCant += 10) {
                                // Calculamos macros si añadimos "candCant" de este alimento
                                $fCal  = $totCal  + ($al->calorias      * $candCant / 100);
                                $fProt = $totProt + ($al->proteinas     * $candCant / 100);
                                $fCarb = $totCarb + ($al->carbohidratos * $candCant / 100);
                                $fGras = $totGras + ($al->grasas        * $candCant / 100);

                                // Calculamos el score => menor es mejor
                                // (distancia relativa al objetivo de cada macro, al cuadrado)
                                $score = 0;
                                if ($calObj  > 0) $score += pow(($fCal  - $calObj ) / $calObj , 2);
                                if ($protObj > 0) $score += pow(($fProt - $protObj) / $protObj, 2);
                                if ($carbObj > 0) $score += pow(($fCarb - $carbObj) / $carbObj, 2);
                                if ($grasObj > 0) $score += pow(($fGras - $grasObj) / $grasObj, 2);

                                // Verificamos si es la mejor combinación encontrada
                                if (is_null($mejorScore) || $score < $mejorScore) {
                                    $mejorScore    = $score;
                                    $mejorAlimento = $al;
                                    $mejorCantidad = $candCant;
                                }
                            }
                        }
                    }

                    // 7.2. Si no encontramos ningún alimento, salimos
                    if (!$mejorAlimento) {
                        break;
                    }

                    // 7.3. Añadimos el mejor alimento
                    $alimentosUsados[] = $mejorAlimento->id;

                    // Verificamos overshoot (ej. 105%)
                    $futureCal  = $totCal  + ($mejorAlimento->calorias      * $mejorCantidad / 100);
                    $futureProt = $totProt + ($mejorAlimento->proteinas     * $mejorCantidad / 100);
                    $futureCarb = $totCarb + ($mejorAlimento->carbohidratos * $mejorCantidad / 100);
                    $futureGras = $totGras + ($mejorAlimento->grasas        * $mejorCantidad / 100);

                    $margen = 1.05;
                    $excesoCal  = ($calObj  > 0 && $futureCal  > $calObj  * $margen);
                    $excesoProt = ($protObj > 0 && $futureProt > $protObj * $margen);
                    $excesoCarb = ($carbObj > 0 && $futureCarb > $carbObj * $margen);
                    $excesoGras = ($grasObj > 0 && $futureGras > $grasObj * $margen);

                    if ($excesoCal || $excesoProt || $excesoCarb || $excesoGras) {
                        // Recalcular factor de reducción
                        $factorCal  = $excesoCal  ? ($calObj  * $margen) / $futureCal  : 1;
                        $factorProt = $excesoProt ? ($protObj * $margen) / $futureProt : 1;
                        $factorCarb = $excesoCarb ? ($carbObj * $margen) / $futureCarb : 1;
                        $factorGras = $excesoGras ? ($grasObj * $margen) / $futureGras : 1;

                        $factorFinal = min($factorCal, $factorProt, $factorCarb, $factorGras);
                        $recalc = floor($mejorCantidad * $factorFinal);

                        if ($recalc < 10) {
                            // Si queda muy poco, no lo añadimos
                            continue;
                        }
                        $mejorCantidad = $recalc;
                    }

                    // Creamos el registro en la BD
                    DietaAlimento::create([
                        'dieta_id'    => $dieta->id,
                        'alimento_id' => $mejorAlimento->id,
                        'dia'         => $dia,
                        'tipo_comida' => $tipoComida,
                        'cantidad'    => $mejorCantidad,
                        'consumido'   => false
                    ]);

                    // Actualizamos totales
                    $totCal  += ($mejorAlimento->calorias      * $mejorCantidad / 100);
                    $totProt += ($mejorAlimento->proteinas     * $mejorCantidad / 100);
                    $totCarb += ($mejorAlimento->carbohidratos * $mejorCantidad / 100);
                    $totGras += ($mejorAlimento->grasas        * $mejorCantidad / 100);

                    // Guardar en el JSON
                    $alimentosSeleccionados[] = [
                        'nombre'        => $mejorAlimento->nombre,
                        'cantidad'      => $mejorCantidad,
                        'calorias'      => round(($mejorAlimento->calorias      * $mejorCantidad) / 100),
                        'proteinas'     => round(($mejorAlimento->proteinas     * $mejorCantidad) / 100, 1),
                        'carbohidratos' => round(($mejorAlimento->carbohidratos * $mejorCantidad) / 100, 1),
                        'grasas'        => round(($mejorAlimento->grasas        * $mejorCantidad) / 100, 1),
                    ];
                    $seAgregoAlimento = true;
                }

                // Asegurar al menos un alimento en Almuerzo y Merienda
                if (!$seAgregoAlimento && in_array($tipoComida, ['Almuerzo', 'Merienda'])) {
                    $alimentoExtra = $user->alimentos()->inRandomOrder()->first();
                    if ($alimentoExtra) {
                        DietaAlimento::create([
                            'dieta_id'    => $dieta->id,
                            'alimento_id' => $alimentoExtra->id,
                            'dia'         => $dia,
                            'tipo_comida' => $tipoComida,
                            'cantidad'    => 100,
                            'consumido'   => false
                        ]);
                        $alimentosSeleccionados[] = [
                            'nombre'        => $alimentoExtra->nombre,
                            'cantidad'      => 100,
                            'calorias'      => round(($alimentoExtra->calorias      * 100) / 100),
                            'proteinas'     => round(($alimentoExtra->proteinas     * 100) / 100, 1),
                            'carbohidratos' => round(($alimentoExtra->carbohidratos * 100) / 100, 1),
                            'grasas'        => round(($alimentoExtra->grasas        * 100) / 100, 1),
                        ];
                    }
                }

                $dietaJson[$dia][$tipoComida] = $alimentosSeleccionados;
            }
        }

        // Guardar en la base de datos
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

        // Alimentos disponibles, sin filtrar por categoría (queremos libertad para “parchar” déficits)
        $alimentosDisponibles = $user->alimentos()->get();

        // Recuperamos el JSON de la dieta
        $dietaJson = json_decode($dieta->dieta, true);

        // Para cada día de la semana, calculamos los totales reales y vemos si hay déficits grandes
        foreach ($dietaJson as $dia => $comidasDia) {
            // 1. Calcular totales actuales de macros
            $totalCalorias      = 0;
            $totalProteinas     = 0;
            $totalCarbohidratos = 0;
            $totalGrasas        = 0;

            // Para no re-calcular a mano, podemos tirar de la tabla DietaAlimento
            // pero si prefieres, recorre $comidasDia (que ya tiene la info).
            // Aquí haré un cálculo rápido desde DB para ser exacto:
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

            // 2. Determinar si estamos por debajo de un umbral (p.ej. 90%) en alguno de los macros
            $umbral = 0.90;
            $necesitaMasCal   = ($totalCalorias      < $caloriasDiarias      * $umbral);
            $necesitaMasProt  = ($totalProteinas     < $proteinasDiarias     * $umbral);
            $necesitaMasCarb  = ($totalCarbohidratos < $carbohidratosDiarias * $umbral);
            $necesitaMasGrasa = ($totalGrasas        < $grasasDiarias        * $umbral);

            // Si no falta nada, seguimos con el siguiente día
            if (!($necesitaMasCal || $necesitaMasProt || $necesitaMasCarb || $necesitaMasGrasa)) {
                continue;
            }

            // 3. Creamos una "comida" extra para parchear el déficit, si no existe
            if (!isset($dietaJson[$dia]['Snack'])) {
                $dietaJson[$dia]['Snack'] = [];
            }

            // 4. Escoger 1 o 2 alimentos para compensar el macro más deficitario
            //    Repetimos el proceso un par de veces para cubrir varios macros
            for ($i = 0; $i < 2; $i++) {
                // Volvemos a calcular déficits actualizados
                $faltanteCal   = max(0, $caloriasDiarias      - $totalCalorias);
                $faltanteProt  = max(0, $proteinasDiarias     - $totalProteinas);
                $faltanteCarb  = max(0, $carbohidratosDiarias - $totalCarbohidratos);
                $faltanteGrasa = max(0, $grasasDiarias        - $totalGrasas);

                // Ratio de déficit
                $ratioCal   = ($caloriasDiarias      > 0) ? $faltanteCal   / $caloriasDiarias      : 0;
                $ratioProt  = ($proteinasDiarias     > 0) ? $faltanteProt  / $proteinasDiarias     : 0;
                $ratioCarb  = ($carbohidratosDiarias > 0) ? $faltanteCarb  / $carbohidratosDiarias : 0;
                $ratioGrasa = ($grasasDiarias        > 0) ? $faltanteGrasa / $grasasDiarias        : 0;

                // Macro más crítico
                $maxRatio = max($ratioCal, $ratioProt, $ratioCarb, $ratioGrasa);
                if ($maxRatio < (1 - $umbral)) {
                    // Si ya no hay ningún macro por debajo del 90%, paramos
                    break;
                }

                // Identificamos el macro que más falta
                if ($maxRatio === $ratioCal) {
                    $macroCritico = 'calorias';
                } elseif ($maxRatio === $ratioProt) {
                    $macroCritico = 'proteinas';
                } elseif ($maxRatio === $ratioCarb) {
                    $macroCritico = 'carbohidratos';
                } else {
                    $macroCritico = 'grasas';
                }

                // Seleccionamos un alimento que sea "rico" en ese macro
                // Por ejemplo, si falta carbohidratos, buscar alimentos con > 50% de calorías provenientes de carbos, etc.
                $alimentoExtra = $this->seleccionarAlimentoPorMacro($alimentosDisponibles, $macroCritico);
                if (!$alimentoExtra) {
                    // Si no encontramos nada adecuado, salimos
                    break;
                }

                // Calculamos una cantidad para no pasarnos demasiado
                $cantidad = $this->calcularCantidadExtra($alimentoExtra, $faltanteCal, $faltanteProt, $faltanteCarb, $faltanteGrasa, $macroCritico);

                // Creamos el registro en la DB
                DietaAlimento::create([
                    'dieta_id'    => $dieta->id,
                    'alimento_id' => $alimentoExtra->id,
                    'dia'         => $dia,
                    'tipo_comida' => 'Snack',
                    'cantidad'    => $cantidad,
                    'consumido'   => false
                ]);

                // Añadimos al JSON
                $dietaJson[$dia]['Snack'][] = [
                    'nombre'        => $alimentoExtra->nombre,
                    'cantidad'      => $cantidad,
                    'calorias'      => round(($alimentoExtra->calorias      * $cantidad) / 100),
                    'proteinas'     => round(($alimentoExtra->proteinas     * $cantidad) / 100, 1),
                    'carbohidratos' => round(($alimentoExtra->carbohidratos * $cantidad) / 100, 1),
                    'grasas'        => round(($alimentoExtra->grasas        * $cantidad) / 100, 1),
                ];

                // Actualizamos los totales
                $totalCalorias      += ($alimentoExtra->calorias      * $cantidad) / 100;
                $totalProteinas     += ($alimentoExtra->proteinas     * $cantidad) / 100;
                $totalCarbohidratos += ($alimentoExtra->carbohidratos * $cantidad) / 100;
                $totalGrasas        += ($alimentoExtra->grasas        * $cantidad) / 100;
            }
        }

        // Guardar el JSON modificado
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
