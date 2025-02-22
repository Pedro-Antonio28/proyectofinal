<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Dieta;
use Illuminate\Auth\Access\HandlesAuthorization;

class DietaPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver la dieta.
     */
    public function view(User $user, Dieta $dieta)
    {
        return $user->id === $dieta->user_id;
    }

    /**
     * Determina si el usuario puede actualizar la dieta.
     */
    public function update(User $user, Dieta $dieta)
    {
        return $user->id === $dieta->user_id;
    }

    // Puedes agregar otros métodos (delete, create, etc.) según lo necesites.
}
