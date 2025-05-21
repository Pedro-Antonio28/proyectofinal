<?php

use App\Models\Dieta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

uses(RefreshDatabase::class);

it('can_create_a_diet', function () {
    $user = User::factory()->create();

    $diet = Dieta::create([
        'user_id' => $user->id,
        'semana' => Carbon::now()->weekOfYear,
        'dieta' => json_encode(['meals' => []]),
    ]);

    expect($diet)->toBeInstanceOf(Dieta::class);
    expect($diet->user_id)->toBe($user->id);
});

it('can_retrieve_current_week_diets', function () {
    $user = User::factory()->create();
    $diet = Dieta::factory()->create([
        'user_id' => $user->id,
        'semana' => Carbon::now()->weekOfYear,
    ]);

    $diets = Dieta::deSemanaActual($user->id)->get();

    expect($diets)->toHaveCount(1);
});
