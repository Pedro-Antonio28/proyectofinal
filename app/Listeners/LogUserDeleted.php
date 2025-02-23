<?php

namespace App\Listeners;

use App\Events\UserDeleted;
use Illuminate\Support\Facades\Log;

class LogUserDeleted
{
    /**
     * Manejar el evento.
     */
    public function handle(UserDeleted $event)
    {
        Log::info("Usuario eliminado: {$event->user->id} - {$event->user->email}");
    }
}
