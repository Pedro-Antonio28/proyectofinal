<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\UserDeleted;
use App\Listeners\LogUserDeleted;

class EventServiceProvider extends ServiceProvider
{
    /**
     * La asignación de eventos a sus listeners.
     *
     * @var array
     */
    protected $listen = [
        UserDeleted::class => [
            LogUserDeleted::class,
        ],
    ];

    /**
     * Registra cualquier evento para tu aplicación.
     */
    public function boot()
    {
        parent::boot();
    }
}
