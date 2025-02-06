<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alimento;

class AlimentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alimentos = [
            ['nombre' => 'Pollo', 'categoria' => 'proteinas', 'imagen' => 'alimentos/pollo.png', 'calorias' => 165, 'proteinas' => 31, 'carbohidratos' => 0, 'grasas' => 4],
            ['nombre' => 'Salmón', 'categoria' => 'proteinas', 'imagen' => 'alimentos/salmon.png', 'calorias' => 208, 'proteinas' => 22, 'carbohidratos' => 0, 'grasas' => 13],
            ['nombre' => 'Lentejas', 'categoria' => 'proteinas', 'imagen' => 'alimentos/lentejas.png', 'calorias' => 116, 'proteinas' => 9, 'carbohidratos' => 20, 'grasas' => 0.4],
            ['nombre' => 'Arroz', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/arroz.png', 'calorias' => 130, 'proteinas' => 2, 'carbohidratos' => 28, 'grasas' => 0.3],
            ['nombre' => 'Pasta', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/pasta.png', 'calorias' => 131, 'proteinas' => 5, 'carbohidratos' => 25, 'grasas' => 1.1],
            ['nombre' => 'Manzana', 'categoria' => 'frutas', 'imagen' => 'alimentos/manzana.png', 'calorias' => 52, 'proteinas' => 0.3, 'carbohidratos' => 14, 'grasas' => 0.2],
            ['nombre' => 'Plátano', 'categoria' => 'frutas', 'imagen' => 'alimentos/platano.png', 'calorias' => 89, 'proteinas' => 1.1, 'carbohidratos' => 23, 'grasas' => 0.3],
            ['nombre' => 'Lechuga', 'categoria' => 'verduras', 'imagen' => 'alimentos/lechuga.png', 'calorias' => 15, 'proteinas' => 1.4, 'carbohidratos' => 2.9, 'grasas' => 0.2],
            ['nombre' => 'Tomate', 'categoria' => 'verduras', 'imagen' => 'alimentos/tomate.png', 'calorias' => 18, 'proteinas' => 0.9, 'carbohidratos' => 3.9, 'grasas' => 0.2],
        ];

        foreach ($alimentos as $alimento) {
            // Verifica si el alimento ya existe antes de crearlo
            Alimento::firstOrCreate(['nombre' => $alimento['nombre']], $alimento);
        }
    }
}
