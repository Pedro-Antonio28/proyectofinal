<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\models\Dieta;
use App\models\User;


class DietasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dieta en formato JSON para todos los usuarios
        $dietaJson = json_encode([
            "Lunes" => [
                "Desayuno" => ["Avena", "Plátano", "Yogur Griego"],
                "Almuerzo" => ["Manzana", "Almendras"],
                "Comida" => ["Pechuga de Pollo", "Arroz Blanco", "Brócoli"],
                "Merienda" => ["Nueces", "Zanahoria"],
                "Cena" => ["Salmón", "Quinoa", "Espinacas"],
                "Snack" => ["Fresas", "Queso Cottage"]
            ],
            "Martes" => [
                "Desayuno" => ["Pan Integral", "Queso Parmesano", "Naranja"],
                "Almuerzo" => ["Kiwi", "Almendras"],
                "Comida" => ["Atún", "Pasta", "Pimiento Rojo"],
                "Merienda" => ["Papas dulces", "Yogur Griego"],
                "Cena" => ["Tofu", "Batata", "Calabacín"],
                "Snack" => ["Cereza", "Garbanzos"]
            ],
            "Miércoles" => [
                "Desayuno" => ["Pan de centeno", "Huevo", "Granada"],
                "Almuerzo" => ["Piña", "Nueces"],
                "Comida" => ["Carne de Ternera", "Cuscús", "Zanahoria"],
                "Merienda" => ["Melón", "Pepino"],
                "Cena" => ["Seitán", "Quinoa", "Berenjena"],
                "Snack" => ["Manzana", "Champiñones"]
            ],
            "Jueves" => [
                "Desayuno" => ["Batata", "Queso Cottage", "Mango"],
                "Almuerzo" => ["Uvas", "Almendras"],
                "Comida" => ["Pavo", "Pasta", "Espinacas"],
                "Merienda" => ["Cereza", "Pan Integral"],
                "Cena" => ["Salmón", "Patatas", "Remolacha"],
                "Snack" => ["Fresas", "Seitán"]
            ],
            "Viernes" => [
                "Desayuno" => ["Pan de centeno", "Yogur Griego", "Piña"],
                "Almuerzo" => ["Kiwi", "Nueces"],
                "Comida" => ["Gambas", "Arroz Blanco", "Coliflor"],
                "Merienda" => ["Melón", "Queso Parmesano"],
                "Cena" => ["Tofu", "Cuscús", "Berenjena"],
                "Snack" => ["Plátano", "Maíz"]
            ],
            "Sábado" => [
                "Desayuno" => ["Avena", "Queso Cottage", "Fresas"],
                "Almuerzo" => ["Naranja", "Almendras"],
                "Comida" => ["Carne de Ternera", "Papas dulces", "Pimiento Rojo"],
                "Merienda" => ["Manzana", "Queso Parmesano"],
                "Cena" => ["Atún", "Batata", "Calabacín"],
                "Snack" => ["Cereza", "Garbanzos"]
            ],
            "Domingo" => [
                "Desayuno" => ["Papas dulces", "Queso Parmesano", "Kiwi"],
                "Almuerzo" => ["Plátano", "Nueces"],
                "Comida" => ["Pechuga de Pollo", "Quinoa", "Zanahoria"],
                "Merienda" => ["Papaya", "Yogur Griego"],
                "Cena" => ["Seitán", "Patatas", "Champiñones"],
                "Snack" => ["Manzana", "Queso Cottage"]
            ]
        ]);

        // Insertar la dieta para los usuarios 1, 2 y 3
        foreach ([1, 2, 3] as $userId) {
            DB::table('dietas')->insert([
                'user_id' => $userId,
                'semana' => Carbon::now()->weekOfYear,
                'dieta' => $dietaJson,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
