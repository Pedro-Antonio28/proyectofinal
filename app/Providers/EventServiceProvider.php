<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\UserDeleted;
use App\Listeners\LogUserDeleted;
use App\Events\PostLiked;
use App\Listeners\SendTelegramNotification;
use App\Events\DietaSolicitada;
use App\Listeners\EnviarDietaPorTelegram;

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

        DietaSolicitada::class => [
            EnviarDietaPorTelegram::class,
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
