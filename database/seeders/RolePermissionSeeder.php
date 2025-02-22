<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $editor = Role::create(['name' => 'editor']);
        $user = Role::create(['name' => 'usuario']);

        // Crear permisos
        $crearPosts = Permission::create(['name' => 'crear posts']);
        $editarPosts = Permission::create(['name' => 'editar posts']);
        $eliminarPosts = Permission::create(['name' => 'eliminar posts']);

        // Asignar permisos a roles
        $admin->permissions()->attach([$crearPosts->id, $editarPosts->id, $eliminarPosts->id]);
        $editor->permissions()->attach([$crearPosts->id, $editarPosts->id]);

        // Asignar rol admin a un usuario específico (por ejemplo, el usuario con ID 1)
        $usuarioAdmin = User::find(1);
        if ($usuarioAdmin) {
            $usuarioAdmin->roles()->attach($admin);
        }
    }
}
