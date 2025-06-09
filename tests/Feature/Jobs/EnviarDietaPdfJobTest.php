<?php

use App\Jobs\EnviarDietaPdfJob;
use App\Mail\DietaPDFMail;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Pest\Laravel\{actingAs};

beforeEach(function () {
    Mail::fake();
    Storage::fake('local');
});

test('job elimina el archivo PDF después del envío', function () {
    $user = User::factory()->create();
    $dieta = [
        'Martes' => [
            'Comida' => [
                ['nombre' => 'Pollo', 'cantidad' => 200, 'calorias' => 150, 'proteinas' => 30],
            ]
        ]
    ];

    // Ejecutamos el job
    (new EnviarDietaPdfJob($user, $dieta))->handle();

    // Comprobamos que no hay PDFs residuales
    $pdfs = collect(Storage::disk('local')->allFiles())
        ->filter(fn ($path) => Str::endsWith($path, '.pdf'));

    expect($pdfs)->toBeEmpty();
});

test('job puede ser despachado correctamente', function () {
    Bus::fake();

    $user = User::factory()->create();
    $dieta = [
        'Miércoles' => [
            'Cena' => [
                ['nombre' => 'Salmón', 'cantidad' => 100, 'calorias' => 200, 'proteinas' => 25],
            ]
        ]
    ];

    EnviarDietaPdfJob::dispatch($user, $dieta);

    Bus::assertDispatched(EnviarDietaPdfJob::class);
});
