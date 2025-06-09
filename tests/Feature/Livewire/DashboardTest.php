<?php

use App\Http\Livewire\Dashboard;
use App\Models\{User, Dieta, DietaAlimento, Alimento, TelegramUser};
use Illuminate\Support\Facades\{Event, Queue};
use App\Events\DietaSolicitada;
use App\Jobs\EnviarDietaPdfJob;
use function Pest\Laravel\actingAs;
use Livewire\Livewire;

beforeEach(function () {
    Queue::fake();
    Event::fake();
});

test('dashboard renders correctly for authenticated user', function () {
    $user = User::factory()->create([
        'calorias_necesarias' => 1800,
        'proteinas' => 120,
        'carbohidratos' => 220,
        'grasas' => 60,
    ]);

    actingAs($user);

    Livewire::test(Dashboard::class)
        ->assertSee('Dieta para')
        ->assertStatus(200);
});

test('dashboard shows food item if present in dieta', function () {
    $user = User::factory()->create();
    $alimento = Alimento::factory()->create(['nombre' => 'Avena']);

    $dia = ucfirst(now()->locale('es')->isoFormat('dddd'));

    $dieta = Dieta::factory()->create([
        'user_id' => $user->id,
        'semana' => now()->weekOfYear,
        'dieta' => json_encode([
            $dia => [
                'Desayuno' => [
                    [
                        'alimento_id' => $alimento->id,
                        'nombre' => $alimento->nombre,
                        'cantidad' => 100,
                        'calorias' => 360,
                        'proteinas' => 12,
                        'carbohidratos' => 60,
                        'grasas' => 7,
                    ]
                ]
            ]
        ])
    ]);

    actingAs($user);

    Livewire::test(Dashboard::class)
        ->assertSee('Avena');
});

test('can store telegram id and dispatch event', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Dashboard::class)
        ->set('nuevoTelegramId', '123456789')
        ->call('guardarTelegramId')
        ->assertSet('mostrarModalTelegram', false);

    expect(TelegramUser::where('user_id', $user->id)->first()->telegram_id)->toBe('123456789');
    Event::assertDispatched(DietaSolicitada::class);
});

test('can enqueue diet email job', function () {
    $user = User::factory()->create();
    $dia = ucfirst(now()->locale('es')->isoFormat('dddd'));

    $dieta = Dieta::factory()->create([
        'user_id' => $user->id,
        'semana' => now()->weekOfYear,
        'dieta' => json_encode([$dia => []])
    ]);

    actingAs($user);

    Livewire::test(Dashboard::class)
        ->call('enviarDietaSemanalPorCorreo');

    Queue::assertPushed(EnviarDietaPdfJob::class);
});

test('can toggle alimento consumed status', function () {
    $user = User::factory()->create();
    $alimento = Alimento::factory()->create();
    $dia = ucfirst(now()->locale('es')->isoFormat('dddd'));

    $dieta = Dieta::factory()->create([
        'user_id' => $user->id,
        'semana' => now()->weekOfYear,
        'dieta' => json_encode([
            $dia => [
                'Desayuno' => [
                    [
                        'alimento_id' => $alimento->id,
                        'nombre' => $alimento->nombre,
                        'cantidad' => 100,
                        'calorias' => 100,
                        'proteinas' => 10,
                        'carbohidratos' => 20,
                        'grasas' => 5,
                    ]
                ]
            ]
        ])
    ]);

    $registro = DietaAlimento::factory()->create([
        'dieta_id' => $dieta->id,
        'alimento_id' => $alimento->id,
        'dia' => $dia,
        'consumido' => false,
        'cantidad' => 100,
    ]);

    actingAs($user);

    Livewire::test(Dashboard::class)
        ->call('toggleAlimento', $alimento->id);

    expect($registro->fresh()->consumido)->toBeTruthy();


});
