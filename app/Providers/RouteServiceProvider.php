<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * La ruta a la que los usuarios serán redirigidos después de iniciar sesión.
     */
    public const HOME = '/seleccionar-alimentos';


    /**
     * Define las rutas de la aplicación.
     */
    public function boot()
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));


        });


    }


}


