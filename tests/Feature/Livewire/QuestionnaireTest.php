<?php

use App\Http\Livewire\Questionnaire;
use App\Models\User;
use function Pest\Laravel\actingAs;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

test('questionnaire renders correctly', function () {
    Livewire::test(Questionnaire::class)
        ->assertStatus(200)
        ->assertSee('Completa tu información');
});


test('cannot proceed if step 1 is invalid', function () {
    Livewire::test(Questionnaire::class)
        ->set('step', 1)
        ->set('gender', '')
        ->call('nextStep')
        ->assertHasErrors(['gender' => 'required']);
});

test('can proceed through all steps with valid data', function () {
    Livewire::test(Questionnaire::class)
        ->set('gender', 'male')->call('nextStep')
        ->set('age', 25)->call('nextStep')
        ->set('peso', 80)->call('nextStep')
        ->set('altura', 180)->call('nextStep')
        ->set('objetivo', 'ganar_musculo')->call('nextStep')
        ->set('actividad', 'moderado')->call('nextStep')
        ->assertSet('step', 6);
});

test('saves questionnaire and redirects', function () {
    Livewire::test(Questionnaire::class)
        ->set('gender', 'female')
        ->set('age', 30)
        ->set('peso', 65)
        ->set('altura', 165)
        ->set('objetivo', 'perder_peso')
        ->set('actividad', 'ligero')
        ->set('step', 6)
        ->call('save')
        ->assertRedirect(route('user.alimentos'));

    $user = $this->user->fresh();
    expect($user->calorias_necesarias)->not->toBeNull();
    expect($user->proteinas)->not->toBeNull();
    expect($user->carbohidratos)->not->toBeNull();
    expect($user->grasas)->not->toBeNull();
});
