<?php

use App\Http\Livewire\UserAlimentos;
use App\Models\User;
use App\Models\Alimento;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render the UserAlimentos component', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(UserAlimentos::class)
        ->assertStatus(200);
});

it('can save selected favorite foods', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $food1 = Alimento::factory()->create();
    $food2 = Alimento::factory()->create();

    Livewire::test(UserAlimentos::class)
        ->set('favoritos', [$food1->id, $food2->id])
        ->call('guardarSeleccion')
        ->assertSessionHas('message', 'Alimentos guardados correctamente.')
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('user_alimentos', [
        'user_id' => $user->id,
        'alimento_id' => $food1->id,
    ]);

    $this->assertDatabaseHas('user_alimentos', [
        'user_id' => $user->id,
        'alimento_id' => $food2->id,
    ]);
});
