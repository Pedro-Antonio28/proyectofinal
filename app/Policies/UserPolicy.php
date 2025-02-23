<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determina si el usuario puede eliminar otros usuarios.
     */
    public function deleteUser(User $user, User $targetUser)
    {
        // Un administrador puede eliminar usuarios, pero no a sí mismo
        if ($user->hasRole('admin') && $user->id !== $targetUser->id) {
            return true;
        }

        return false;
    }

}
