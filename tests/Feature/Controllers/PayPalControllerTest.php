<?php

use App\Models\User;
use Illuminate\Support\Facades\Session;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

afterEach(function () {
    \Mockery::close();
});

test('cancelTransaction redirects with error message', function () {
    $response = get(route('paypal.cancel'));

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('error', 'El pago fue cancelado.');
});

test('paypalcreate redirects to PayPal approval url on success', function () {
    $mock = \Mockery::mock('overload:' . PayPalClient::class);

    $mock->shouldReceive('setApiCredentials')->once();
    $mock->shouldReceive('getAccessToken')->once()->andReturn('fake-access-token');
    $mock->shouldReceive('setAccessToken')->once()->with('fake-access-token');
    $mock->shouldReceive('createOrder')->once()->andReturn([
        'links' => [
            ['rel' => 'self', 'href' => 'http://localhost/self'],
            ['rel' => 'approve', 'href' => 'https://paypal.com/approve'],
        ]
    ]);

    $response = get(route('paypal.create'));

    $response->assertRedirect('https://paypal.com/approve');
});

test('paypalcreate redirects back with error if PayPal link is missing', function () {
    $mock = \Mockery::mock('overload:' . PayPalClient::class);

    $mock->shouldReceive('setApiCredentials')->once();
    $mock->shouldReceive('getAccessToken')->once()->andReturn('fake-access-token');
    $mock->shouldReceive('setAccessToken')->once()->with('fake-access-token');
    $mock->shouldReceive('createOrder')->once()->andReturn([
        'links' => [
            ['rel' => 'self', 'href' => 'http://localhost/self'],
        ]
    ]);

    $response = get(route('paypal.create'));

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('error', 'Error al iniciar la transacción con PayPal.');
});

test('paypal.cancel redirige con mensaje de cancelación', function () {
    $response = get(route('paypal.cancel'));

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('error', 'El pago fue cancelado.');
});
