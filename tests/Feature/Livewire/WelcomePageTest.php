<?php

use App\Http\Livewire\WelcomePage;
use Livewire\Livewire;

test('welcome page renders correctly', function () {
    Livewire::test(WelcomePage::class)
        ->assertStatus(200)
        ->assertSee('Calorix')
        ->assertSee('Bienvenido')
        ->assertSee('Plan Premium');
});
