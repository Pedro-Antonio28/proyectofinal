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
            // 🔹 PROTEÍNAS
            ['nombre' => 'Pechuga de Pollo', 'categoria' => 'proteinas', 'imagen' => 'alimentos/pechuga_pollo.png', 'calorias' => 165, 'proteinas' => 31, 'carbohidratos' => 0, 'grasas' => 4],
            ['nombre' => 'Salmón', 'categoria' => 'proteinas', 'imagen' => 'alimentos/salmon.png', 'calorias' => 208, 'proteinas' => 22, 'carbohidratos' => 0, 'grasas' => 13],
            ['nombre' => 'Carne de Ternera', 'categoria' => 'proteinas', 'imagen' => 'alimentos/carne_ternera.png', 'calorias' => 250, 'proteinas' => 26, 'carbohidratos' => 0, 'grasas' => 17],
            ['nombre' => 'Atún', 'categoria' => 'proteinas', 'imagen' => 'alimentos/atun.png', 'calorias' => 116, 'proteinas' => 25, 'carbohidratos' => 0, 'grasas' => 1],
            ['nombre' => 'Huevo', 'categoria' => 'proteinas', 'imagen' => 'alimentos/huevo.png', 'calorias' => 143, 'proteinas' => 13, 'carbohidratos' => 1.1, 'grasas' => 10],
            ['nombre' => 'Tofu', 'categoria' => 'proteinas', 'imagen' => 'alimentos/tofu.png', 'calorias' => 76, 'proteinas' => 8, 'carbohidratos' => 1.9, 'grasas' => 4.8],
            ['nombre' => 'Lentejas', 'categoria' => 'proteinas', 'imagen' => 'alimentos/lentejas.png', 'calorias' => 116, 'proteinas' => 9, 'carbohidratos' => 20, 'grasas' => 0.4],

            // 🔹 CARBOHIDRATOS
            ['nombre' => 'Arroz Blanco', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/arroz.png', 'calorias' => 130, 'proteinas' => 2, 'carbohidratos' => 28, 'grasas' => 0.3],
            ['nombre' => 'Pasta', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/pasta.png', 'calorias' => 131, 'proteinas' => 5, 'carbohidratos' => 25, 'grasas' => 1.1],
            ['nombre' => 'Pan Integral', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/pan_integral.png', 'calorias' => 247, 'proteinas' => 13, 'carbohidratos' => 41, 'grasas' => 3.4],
            ['nombre' => 'Patatas', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/patatas.png', 'calorias' => 77, 'proteinas' => 2, 'carbohidratos' => 17, 'grasas' => 0.1],
            ['nombre' => 'Avena', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/avena.png', 'calorias' => 389, 'proteinas' => 17, 'carbohidratos' => 66, 'grasas' => 7],

            // 🔹 FRUTAS
            ['nombre' => 'Manzana', 'categoria' => 'frutas', 'imagen' => 'alimentos/manzana.png', 'calorias' => 52, 'proteinas' => 0.3, 'carbohidratos' => 14, 'grasas' => 0.2],
            ['nombre' => 'Plátano', 'categoria' => 'frutas', 'imagen' => 'alimentos/platano.png', 'calorias' => 89, 'proteinas' => 1.1, 'carbohidratos' => 23, 'grasas' => 0.3],
            ['nombre' => 'Naranja', 'categoria' => 'frutas', 'imagen' => 'alimentos/naranja.png', 'calorias' => 47, 'proteinas' => 1, 'carbohidratos' => 12, 'grasas' => 0.1],
            ['nombre' => 'Fresas', 'categoria' => 'frutas', 'imagen' => 'alimentos/fresas.png', 'calorias' => 32, 'proteinas' => 0.7, 'carbohidratos' => 7.7, 'grasas' => 0.3],

            // 🔹 VERDURAS
            ['nombre' => 'Lechuga', 'categoria' => 'verduras', 'imagen' => 'alimentos/lechuga.png', 'calorias' => 15, 'proteinas' => 1.4, 'carbohidratos' => 2.9, 'grasas' => 0.2],
            ['nombre' => 'Tomate', 'categoria' => 'verduras', 'imagen' => 'alimentos/tomate.png', 'calorias' => 18, 'proteinas' => 0.9, 'carbohidratos' => 3.9, 'grasas' => 0.2],
            ['nombre' => 'Brócoli', 'categoria' => 'verduras', 'imagen' => 'alimentos/brocoli.png', 'calorias' => 34, 'proteinas' => 2.8, 'carbohidratos' => 6.6, 'grasas' => 0.4],
            ['nombre' => 'Espinacas', 'categoria' => 'verduras', 'imagen' => 'alimentos/espinacas.png', 'calorias' => 23, 'proteinas' => 2.9, 'carbohidratos' => 3.6, 'grasas' => 0.4],
        ];

        foreach ($alimentos as $alimento) {
            // Verifica si el alimento ya existe antes de crearlo
            Alimento::firstOrCreate(['nombre' => $alimento['nombre']], $alimento);
        }
    }
}
