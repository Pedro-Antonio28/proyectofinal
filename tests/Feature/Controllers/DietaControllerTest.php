<?php

use App\Models\User;
use App\Models\Dieta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

uses(RefreshDatabase::class);

use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::middleware(['web'])->group(base_path('routes/web.php'));
});

it('returns an error if there is no diet for the week', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('pdf.dieta', ['dia' => 'Lunes']))

        ->assertRedirect()
        ->assertSessionHas('error', 'No se encontró dieta para la semana actual.');
});

it('downloads a PDF if a diet exists', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Dieta::factory()->create([
        'user_id' => $user->id,
        'semana' => Carbon::now()->weekOfYear,
        'dieta' => json_encode(['Lunes' => []]),
    ]);

    $this->get(route('pdf.dieta', ['dia' => 'Lunes']))

        ->assertStatus(200)
        ->assertHeader('content-type', 'application/pdf');
});
