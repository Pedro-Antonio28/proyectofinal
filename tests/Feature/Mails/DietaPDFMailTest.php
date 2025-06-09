<?php

use App\Mail\DietaPDFMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Email;
use function Pest\Laravel\assertMailSent;

beforeEach(function () {
    $this->user = \App\Models\User::factory()->create(['name' => 'Pedro']);
    $this->dia = now()->format('l');

    $this->dietaJson = [
        'Lunes' => [
            'Desayuno' => [
                [
                    'nombre' => 'Huevos revueltos',
                    'cantidad' => '2',
                    'calorias' => 200,
                    'proteinas' => 15,
                    'carbohidratos' => 1,
                    'grasas' => 10,
                ],
            ],
        ],
    ];
});

test('it construye correctamente el mailable', function () {
    $mail = new DietaPDFMail($this->user, $this->dietaJson, $this->dia);

    expect($mail)->toBeInstanceOf(DietaPDFMail::class);
    expect($mail->user)->toBe($this->user);
    expect($mail->dietaJson)->toBe($this->dietaJson);
    expect($mail->dia)->toBe($this->dia);
});

test('it renderiza el html esperado con datos de dieta', function () {
    $mail = new DietaPDFMail($this->user, $this->dietaJson, $this->dia);

    $render = $mail->render();

    expect($render)->toContain("Aquí tienes tu dieta **semanal** en formato PDF");
    expect($render)->toContain("Gracias por usar Laravel 💚");
});






