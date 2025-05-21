<?php

use App\Models\User;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Alimento;
use App\Services\DietaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'calorias_necesarias' => 2500,
        'proteinas' => 150,
        'carbohidratos' => 300,
        'grasas' => 80
    ]);

    $this->service = new DietaService();
});

it('creates a weekly diet for a user', function () {
    Carbon::setTestNow(Carbon::create(2024, 4, 1)); // Fijar fecha de prueba

    $dieta = $this->service->generarDietaSemanal($this->user);

    expect($dieta)->toBeInstanceOf(Dieta::class)
        ->and($dieta->user_id)->toBe($this->user->id)
        ->and($dieta->semana)->toBe(Carbon::now()->weekOfYear);
});

it('removes previous diet records before creating a new one', function () {
    $dieta = Dieta::factory()->create(['user_id' => $this->user->id, 'semana' => Carbon::now()->weekOfYear]);

    DietaAlimento::factory()->count(5)->create(['dieta_id' => $dieta->id]);

    $this->service->generarDietaSemanal($this->user);

    expect(DietaAlimento::where('dieta_id', $dieta->id)->count())->toBe(0);
});

it('generates a diet with at least one meal per day', function () {
    $this->service->generarDietaSemanal($this->user);

    $dieta = Dieta::where('user_id', $this->user->id)->first();

    expect(json_decode($dieta->dieta, true))->toHaveKeys(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']);
});
