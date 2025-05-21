<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{

    public function run(): void
    {
        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $nutricionistaRole = Role::firstOrCreate(['name' => 'nutricionista']);
        $usuarioRole = Role::firstOrCreate(['name' => 'usuario']);

        // Crear usuarios y asignar roles
        $admin = User::factory()->create([
            'name' => 'Admin Master',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach($adminRole);

        $nutri = User::factory()->create([
            'name' => 'Nutricionista Pro',
            'email' => 'nutricionista@example.com',
            'password' => Hash::make('password'),
        ]);
        $nutri->roles()->attach($nutricionistaRole);

        $usuario = User::factory()->create([
            'name' => 'Usuario Normal',
            'email' => 'usuario@example.com',
            'password' => Hash::make('password'),
        ]);
        $usuario->roles()->attach($usuarioRole);

        // Crear posts
        Post::factory(10)->create([
            'user_id' => $usuario->id
        ]);
    }

}

