<?php

use App\Http\Livewire\AgregarAlimento;
use App\Models\User;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Alimento;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render the Add Food component', function () {
    Livewire::test(AgregarAlimento::class, ['dia' => 1, 'tipoComida' => 'desayuno'])
        ->assertStatus(200);
});

