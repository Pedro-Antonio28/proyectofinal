<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserDeleted
{
    use Dispatchable, SerializesModels;

    public $user;

    /**
     * Crear una nueva instancia del evento.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
