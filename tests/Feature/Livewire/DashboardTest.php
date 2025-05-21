<?php

use App\Http\Livewire\Dashboard;
use App\Models\User;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Alimento;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render the Dashboard component', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Dashboard::class)
        ->assertStatus(200);
});

it('can toggle food consumption', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $dieta = Dieta::factory()->create(['user_id' => $user->id]);
    $alimento = Alimento::factory()->create();
    $dietaAlimento = DietaAlimento::factory()->create([
        'dieta_id' => $dieta->id,
        'alimento_id' => $alimento->id,
        'dia' => 'Lunes',
        'consumido' => false,
    ]);

    Livewire::test(Dashboard::class)
        ->call('toggleAlimento', $alimento->id)
        ->assertSee($alimento->id); // Asegurar que el alimento está en la UI

    $this->assertDatabaseHas('dieta_alimentos', [
        'id' => $dietaAlimento->id,
        'consumido' => true,
    ]);
});
