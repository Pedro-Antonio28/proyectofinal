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
        // Admin puede ver cualquier dieta
        if ($user->hasRole('admin')) {
            return true;
        }

        // Nutricionista puede ver la dieta de sus clientes
        if ($user->hasRole('nutricionista') && $user->clientes->contains($dieta->user_id)) {
            return true;
        }

        // El propio usuario puede ver su dieta
        return $user->id === $dieta->user_id;
    }

    public function update(User $user, Dieta $dieta)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('nutricionista') && $user->clientes->contains($dieta->user_id)) {
            return true;
        }

        return $user->id === $dieta->user_id;
    }

    public function delete(User $user, Dieta $dieta)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('nutricionista') && $user->clientes->contains($dieta->user_id)) {
            return true;
        }

        return $user->id === $dieta->user_id;
    }




}
