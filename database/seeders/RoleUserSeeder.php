<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir los roles
        $roles = [
            'admin' => 1,
            'nutricionista' => 2,
            'usuario' => 3
        ];

        // Asignar los roles a los usuarios
        DB::table('role_user')->insert([
            [
                'user_id' => 1, // Admin
                'role_id' => $roles['admin'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2, // Nutricionista
                'role_id' => $roles['nutricionista'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3, // Usuario (Cliente)
                'role_id' => $roles['usuario'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
