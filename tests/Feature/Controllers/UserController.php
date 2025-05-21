<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('saves the user questionnaire successfully', function () {
$user = User::factory()->create();

actingAs($user)
->post(route('user.questionnaire.save'), [
'gender' => 'male',
'age' => 25,
'peso' => 70,
'altura' => 175,
'objetivo' => 'perder peso',
'actividad' => 'moderada',
])
->assertRedirect(route('dashboard'));

$this->assertDatabaseHas('users', [
'id' => $user->id,
'gender' => 'male',
'age' => 25,
'peso' => 70,
'altura' => 175,
'objetivo' => 'perder peso',
'actividad' => 'moderada',
]);
});

it('fails when required fields are missing in the questionnaire', function () {
$user = User::factory()->create();

actingAs($user)
->post(route('user.questionnaire.save'), [])
->assertSessionHasErrors(['gender', 'age', 'peso', 'altura', 'objetivo', 'actividad']);
});
