<?php

use App\Http\Livewire\Questionnaire;
use App\Models\User;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render the Questionnaire component', function () {
    Livewire::test(Questionnaire::class)
        ->assertStatus(200);
});

it('can complete the questionnaire and save user data', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Questionnaire::class)
        ->set('gender', 'male')
        ->set('age', 25)
        ->set('peso', 70)
        ->set('altura', 175)
        ->set('objetivo', 'ganar_musculo')
        ->set('actividad', 'moderado')
        ->call('save')
        ->assertSessionHas('message', 'Cuestionario completado correctamente y datos guardados.')
        ->assertRedirect(route('user.alimentos'));

    $user->refresh();

    expect($user->calorias_necesarias)->toBeGreaterThan(1500);
});
