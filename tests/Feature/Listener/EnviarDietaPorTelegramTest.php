<?php

use App\Events\DietaSolicitada;
use App\Listeners\EnviarDietaPorTelegram;
use App\Models\User;
use App\Models\Dieta;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

beforeEach(function () {
    Carbon::setTestNow(now()->locale('es')->startOfWeek()); // lunes
});

test('no envía mensaje si el usuario no tiene telegram_id', function () {
    Http::fake();

    $user = User::factory()->create();
    Dieta::factory()->create([
        'user_id' => $user->id,
        'dieta' => json_encode([
            'Lunes' => [
                'Desayuno' => [['nombre' => 'Avena', 'cantidad' => 100]]
            ]
        ])
    ]);

    $listener = new EnviarDietaPorTelegram();
    $listener->handle(new DietaSolicitada($user));

    Http::assertNothingSent();
});

test('no envía mensaje si no hay dieta semanal', function () {
    Http::fake();

    $user = User::factory()->create();
    TelegramUser::factory()->create(['user_id' => $user->id, 'telegram_id' => '123456789']);

    $listener = new EnviarDietaPorTelegram();
    $listener->handle(new DietaSolicitada($user));

    Http::assertNothingSent();
});

test('no envía mensaje si no hay datos para el día actual', function () {
    Http::fake();

    $user = User::factory()->create();
    TelegramUser::factory()->create(['user_id' => $user->id, 'telegram_id' => '123456789']);
    Dieta::factory()->create([
        'user_id' => $user->id,
        'dieta' => json_encode(['Martes' => []]) // lunes actual sin datos
    ]);

    $listener = new EnviarDietaPorTelegram();
    $listener->handle(new DietaSolicitada($user));

    Http::assertNothingSent();
});

test('envía mensaje de dieta al usuario por telegram', function () {
    Http::fake();

    $user = User::factory()->create();
    TelegramUser::factory()->create(['user_id' => $user->id, 'telegram_id' => '123456789']);
    Dieta::factory()->create([
        'user_id' => $user->id,
        'dieta' => json_encode([
            'Lunes' => [
                'Desayuno' => [
                    ['nombre' => 'Avena', 'cantidad' => 100],
                    ['nombre' => 'Leche', 'cantidad' => 200]
                ],
                'Cena' => [
                    ['nombre' => 'Ensalada', 'cantidad' => 150]
                ]
            ]
        ])
    ]);

    $listener = new EnviarDietaPorTelegram();
    $listener->handle(new DietaSolicitada($user));

    Http::assertSent(function ($request) {
        return str_contains($request['text'], '🍽 Tu dieta para hoy (Lunes)') &&
            str_contains($request['text'], '🍴 Desayuno') &&
            str_contains($request['text'], '100g Avena') &&
            str_contains($request['text'], '200g Leche') &&
            str_contains($request['text'], '🍴 Cena') &&
            str_contains($request['text'], '150g Ensalada') &&
            $request['chat_id'] === '123456789';
    });
});


