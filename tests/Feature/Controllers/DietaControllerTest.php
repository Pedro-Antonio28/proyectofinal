<?php

use App\Models\User;
use App\Models\Dieta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use function Pest\Laravel\{get, actingAs};

test('user can download dieta PDF for current week', function () {
    $user = User::factory()->create();
    actingAs($user);

    $semanaActual = now()->weekOfYear;

    $dietaJson = [
        'Lunes' => [
            'Desayuno' => [
                ['nombre' => 'Avena', 'cantidad' => 100, 'calorias' => 380],
            ],
            'Comida' => [
                ['nombre' => 'Pollo', 'cantidad' => 150, 'calorias' => 250],
            ],
        ]
    ];

    Dieta::factory()->create([
        'user_id' => $user->id,
        'semana' => $semanaActual,
        'dieta' => json_encode($dietaJson),
    ]);

    $response = get(route('pdf.dieta', ['dia' => 'Lunes']));

    $response->assertStatus(200);
    $response->assertHeader('content-disposition', 'attachment; filename=dieta_Lunes.pdf');
    $response->assertSee('PDF'); // Confirma que se genera algo tipo PDF aunque no validamos el binario
});

test('user gets redirected if no dieta found', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = get(route('pdf.dieta', ['dia' => 'Martes']));

    $response->assertRedirect();
    $response->assertSessionHas('error', 'No se encontró dieta para la semana actual.');
});
