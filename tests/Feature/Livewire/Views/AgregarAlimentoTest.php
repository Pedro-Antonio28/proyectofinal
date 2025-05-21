<?php

use App\Http\Livewire\AgregarAlimento;
use App\Models\User;
use App\Models\Alimento;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the component', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(AgregarAlimento::class, ['dia' => 'Lunes', 'tipoComida' => 'Almuerzo'])
        ->assertStatus(200);
});

