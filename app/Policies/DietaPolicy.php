<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Dieta;

class DietaPolicy
{
    /**
     * Determina si el usuario puede ver la dieta de un cliente.
     */
    public function view(User $user, Dieta $dieta)
    {
        // Un admin puede ver cualquier dieta
        if ($user->hasRole('admin')) {
            return true;
        }

        // Un nutricionista solo puede ver la dieta de sus clientes
        return $user->clientes->contains($dieta->user_id);
    }

    /**
     * Determina si el usuario puede editar la dieta.
     */
    public function update(User $user, Dieta $dieta)
    {
        // Un admin puede editar cualquier dieta
        if ($user->hasRole('admin')) {
            return true;
        }

        // Un nutricionista solo puede editar la dieta de sus clientes
        return $user->clientes->contains($dieta->user_id);
    }

    /**
     * Determina si el usuario puede eliminar un alimento de la dieta.
     */
    public function delete(User $user, Dieta $dieta)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Verificar si el usuario es un nutricionista y la dieta pertenece a uno de sus clientes
        return $user->clientes()->where('users.id', $dieta->user_id)->exists();

    }



}
