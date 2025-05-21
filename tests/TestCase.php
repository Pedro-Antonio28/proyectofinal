<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Registrar el middleware en las pruebas
        $this->app['router']->aliasMiddleware('role', \App\Middleware\RoleMiddleware::class);


        $this->app['router']->middleware('web')->group(base_path('routes/web.php'));

    }
}
