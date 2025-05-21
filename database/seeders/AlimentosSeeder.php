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
            ['nombre' => 'Garbanzos', 'categoria' => 'proteinas', 'imagen' => 'alimentos/garbanzos.png', 'calorias' => 164, 'proteinas' => 9, 'carbohidratos' => 27, 'grasas' => 2.6],
            ['nombre' => 'Queso Cottage', 'categoria' => 'proteinas', 'imagen' => 'alimentos/queso_cottage.png', 'calorias' => 98, 'proteinas' => 11, 'carbohidratos' => 3.4, 'grasas' => 4.3],
            ['nombre' => 'Pavo', 'categoria' => 'proteinas', 'imagen' => 'alimentos/pavo.png', 'calorias' => 135, 'proteinas' => 29, 'carbohidratos' => 0, 'grasas' => 1.5],
            ['nombre' => 'Gambas', 'categoria' => 'proteinas', 'imagen' => 'alimentos/gambas.png', 'calorias' => 99, 'proteinas' => 24, 'carbohidratos' => 0, 'grasas' => 0.3],
            ['nombre' => 'Almendras', 'categoria' => 'proteinas', 'imagen' => 'alimentos/almendras.png', 'calorias' => 576, 'proteinas' => 21, 'carbohidratos' => 22, 'grasas' => 49],
            ['nombre' => 'Nueces', 'categoria' => 'proteinas', 'imagen' => 'alimentos/nueces.png', 'calorias' => 654, 'proteinas' => 15, 'carbohidratos' => 14, 'grasas' => 65],
            ['nombre' => 'Queso Parmesano', 'categoria' => 'proteinas', 'imagen' => 'alimentos/queso_parmesano.png', 'calorias' => 431, 'proteinas' => 38, 'carbohidratos' => 4, 'grasas' => 29],
            ['nombre' => 'Yogur Griego', 'categoria' => 'proteinas', 'imagen' => 'alimentos/yogur_griego.png', 'calorias' => 59, 'proteinas' => 10, 'carbohidratos' => 3.6, 'grasas' => 0.4],
            ['nombre' => 'Seitán', 'categoria' => 'proteinas', 'imagen' => 'alimentos/seitan.png', 'calorias' => 121, 'proteinas' => 21, 'carbohidratos' => 4, 'grasas' => 2],


            // 🔹 CARBOHIDRATOS
            ['nombre' => 'Arroz Blanco', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/arroz.png', 'calorias' => 130, 'proteinas' => 2, 'carbohidratos' => 28, 'grasas' => 0.3],
            ['nombre' => 'Pasta', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/pasta.png', 'calorias' => 131, 'proteinas' => 5, 'carbohidratos' => 25, 'grasas' => 1.1],
            ['nombre' => 'Pan Integral', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/pan_integral.png', 'calorias' => 247, 'proteinas' => 13, 'carbohidratos' => 41, 'grasas' => 3.4],
            ['nombre' => 'Patatas', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/patatas.png', 'calorias' => 77, 'proteinas' => 2, 'carbohidratos' => 17, 'grasas' => 0.1],
            ['nombre' => 'Avena', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/avena.png', 'calorias' => 389, 'proteinas' => 17, 'carbohidratos' => 66, 'grasas' => 7],
            ['nombre' => 'Batata', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/batata.png', 'calorias' => 86, 'proteinas' => 1.6, 'carbohidratos' => 20, 'grasas' => 0.1],
            ['nombre' => 'Quinoa', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/quinoa.png', 'calorias' => 120, 'proteinas' => 4.1, 'carbohidratos' => 21.3, 'grasas' => 1.9],
            ['nombre' => 'Cuscús', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/cuscus.png', 'calorias' => 112, 'proteinas' => 3.8, 'carbohidratos' => 23, 'grasas' => 0.2],
            ['nombre' => 'Centeno', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/centeno.png', 'calorias' => 335, 'proteinas' => 10, 'carbohidratos' => 73, 'grasas' => 1.6],
            ['nombre' => 'Mijo', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/mijo.png', 'calorias' => 378, 'proteinas' => 11, 'carbohidratos' => 72, 'grasas' => 4.2],
            ['nombre' => 'Papas dulces', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/papas_dulces.png', 'calorias' => 86, 'proteinas' => 1.6, 'carbohidratos' => 20, 'grasas' => 0.1],
            ['nombre' => 'Maíz', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/maiz.png', 'calorias' => 96, 'proteinas' => 3.4, 'carbohidratos' => 21, 'grasas' => 1.5],
            ['nombre' => 'Trigo Sarraceno', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/trigo_sarraceno.png', 'calorias' => 343, 'proteinas' => 13, 'carbohidratos' => 72, 'grasas' => 3.4],
            ['nombre' => 'Pan de centeno', 'categoria' => 'carbohidratos', 'imagen' => 'alimentos/pan_centeno.png', 'calorias' => 259, 'proteinas' => 8, 'carbohidratos' => 48, 'grasas' => 3.3],


            // 🔹 FRUTAS
            ['nombre' => 'Manzana', 'categoria' => 'frutas', 'imagen' => 'alimentos/manzana.png', 'calorias' => 52, 'proteinas' => 0.3, 'carbohidratos' => 14, 'grasas' => 0.2],
            ['nombre' => 'Plátano', 'categoria' => 'frutas', 'imagen' => 'alimentos/platano.png', 'calorias' => 89, 'proteinas' => 1.1, 'carbohidratos' => 23, 'grasas' => 0.3],
            ['nombre' => 'Naranja', 'categoria' => 'frutas', 'imagen' => 'alimentos/naranja.png', 'calorias' => 47, 'proteinas' => 1, 'carbohidratos' => 12, 'grasas' => 0.1],
            ['nombre' => 'Fresas', 'categoria' => 'frutas', 'imagen' => 'alimentos/fresas.png', 'calorias' => 32, 'proteinas' => 0.7, 'carbohidratos' => 7.7, 'grasas' => 0.3],
            ['nombre' => 'Mango', 'categoria' => 'frutas', 'imagen' => 'alimentos/mango.png', 'calorias' => 60, 'proteinas' => 0.8, 'carbohidratos' => 15, 'grasas' => 0.4],
            ['nombre' => 'Kiwi', 'categoria' => 'frutas', 'imagen' => 'alimentos/kiwi.png', 'calorias' => 41, 'proteinas' => 1, 'carbohidratos' => 10, 'grasas' => 0.4],
            ['nombre' => 'Piña', 'categoria' => 'frutas', 'imagen' => 'alimentos/pina.png', 'calorias' => 50, 'proteinas' => 0.5, 'carbohidratos' => 13, 'grasas' => 0.1],
            ['nombre' => 'Cereza', 'categoria' => 'frutas', 'imagen' => 'alimentos/cereza.png', 'calorias' => 63, 'proteinas' => 1.1, 'carbohidratos' => 16, 'grasas' => 0.2],
            ['nombre' => 'Granada', 'categoria' => 'frutas', 'imagen' => 'alimentos/granada.png', 'calorias' => 83, 'proteinas' => 1.7, 'carbohidratos' => 19, 'grasas' => 1.2],
            ['nombre' => 'Uvas', 'categoria' => 'frutas', 'imagen' => 'alimentos/uvas.png', 'calorias' => 69, 'proteinas' => 0.7, 'carbohidratos' => 18, 'grasas' => 0.2],
            ['nombre' => 'Papaya', 'categoria' => 'frutas', 'imagen' => 'alimentos/papaya.png', 'calorias' => 43, 'proteinas' => 0.5, 'carbohidratos' => 11, 'grasas' => 0.3],
            ['nombre' => 'Melón', 'categoria' => 'frutas', 'imagen' => 'alimentos/melon.png', 'calorias' => 34, 'proteinas' => 0.8, 'carbohidratos' => 8, 'grasas' => 0.2],


            // 🔹 VERDURAS
            ['nombre' => 'Brócoli', 'categoria' => 'verduras', 'imagen' => 'alimentos/brocoli.png', 'calorias' => 34, 'proteinas' => 2.8, 'carbohidratos' => 6.6, 'grasas' => 0.4],
            ['nombre' => 'Espinacas', 'categoria' => 'verduras', 'imagen' => 'alimentos/espinacas.png', 'calorias' => 23, 'proteinas' => 2.9, 'carbohidratos' => 3.6, 'grasas' => 0.4],
            ['nombre' => 'Pimiento Rojo', 'categoria' => 'verduras', 'imagen' => 'alimentos/pimiento_rojo.png', 'calorias' => 31, 'proteinas' => 1, 'carbohidratos' => 6, 'grasas' => 0.3],
            ['nombre' => 'Zanahoria', 'categoria' => 'verduras', 'imagen' => 'alimentos/zanahoria.png', 'calorias' => 41, 'proteinas' => 0.9, 'carbohidratos' => 10, 'grasas' => 0.2],
            ['nombre' => 'Coliflor', 'categoria' => 'verduras', 'imagen' => 'alimentos/coliflor.png', 'calorias' => 25, 'proteinas' => 1.9, 'carbohidratos' => 5, 'grasas' => 0.3],
            ['nombre' => 'Berenjena', 'categoria' => 'verduras', 'imagen' => 'alimentos/berenjena.png', 'calorias' => 25, 'proteinas' => 1, 'carbohidratos' => 6, 'grasas' => 0.2],
            ['nombre' => 'Pepino', 'categoria' => 'verduras', 'imagen' => 'alimentos/pepino.png', 'calorias' => 16, 'proteinas' => 0.7, 'carbohidratos' => 3.6, 'grasas' => 0.1],
            ['nombre' => 'Calabacín', 'categoria' => 'verduras', 'imagen' => 'alimentos/calabacin.png', 'calorias' => 17, 'proteinas' => 1.2, 'carbohidratos' => 3.1, 'grasas' => 0.3],
            ['nombre' => 'Champiñones', 'categoria' => 'verduras', 'imagen' => 'alimentos/champinones.png', 'calorias' => 22, 'proteinas' => 3.1, 'carbohidratos' => 3.3, 'grasas' => 0.3],
            ['nombre' => 'Remolacha', 'categoria' => 'verduras', 'imagen' => 'alimentos/remolacha.png', 'calorias' => 43, 'proteinas' => 1.6, 'carbohidratos' => 10, 'grasas' => 0.2],

            // 🔹 GRASAS
            ['nombre' => 'Aceite de Oliva', 'categoria' => 'grasas', 'imagen' => 'alimentos/aceite_oliva.png', 'calorias' => 884, 'proteinas' => 0, 'carbohidratos' => 0, 'grasas' => 100],
            ['nombre' => 'Aguacate', 'categoria' => 'grasas', 'imagen' => 'alimentos/aguacate.png', 'calorias' => 160, 'proteinas' => 2, 'carbohidratos' => 9, 'grasas' => 15],
            ['nombre' => 'Mantequilla', 'categoria' => 'grasas', 'imagen' => 'alimentos/mantequilla.png', 'calorias' => 717, 'proteinas' => 0.85, 'carbohidratos' => 0.06, 'grasas' => 81],
            ['nombre' => 'Crema de cacahuete', 'categoria' => 'grasas', 'imagen' => 'alimentos/crema_cacahuete.png', 'calorias' => 588, 'proteinas' => 25, 'carbohidratos' => 20, 'grasas' => 50],
            ['nombre' => 'Aceitunas', 'categoria' => 'grasas', 'imagen' => 'alimentos/aceitunas.png', 'calorias' => 115, 'proteinas' => 0.8, 'carbohidratos' => 6.3, 'grasas' => 10.7],


        ];

        foreach ($alimentos as $alimento) {
            Alimento::firstOrCreate(['nombre' => $alimento['nombre']], $alimento);
        }
    }
}
