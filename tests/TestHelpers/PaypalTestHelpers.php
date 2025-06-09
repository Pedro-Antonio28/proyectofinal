<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mockery as m;


function loginUser()
{
    $user = User::factory()->create();
    Auth::login($user);
    test()->user = $user;
}

function mockPaypalCreateSuccess()
{
    $mock = m::mock(\Srmklive\PayPal\Services\PayPal::class);

    $mock->shouldReceive('setApiCredentials')->andReturnSelf();
    $mock->shouldReceive('getAccessToken')->andReturn('fake-access-token');
    $mock->shouldReceive('setAccessToken')->with('fake-access-token')->andReturnSelf();
    $mock->shouldReceive('createOrder')->andReturn([
        'links' => [
            ['rel' => 'self', 'href' => 'http://localhost/self'],
            ['rel' => 'approve', 'href' => 'https://paypal.com/approve'],
        ]
    ]);

    app()->instance(\Srmklive\PayPal\Services\PayPal::class, $mock);
}

function mockPaypalCreateFail()
{
    $mock = m::mock(\Srmklive\PayPal\Services\PayPal::class);

    $mock->shouldReceive('setApiCredentials')->andReturnSelf();
    $mock->shouldReceive('getAccessToken')->andReturn('fake-access-token');
    $mock->shouldReceive('setAccessToken')->with('fake-access-token')->andReturnSelf();
    $mock->shouldReceive('createOrder')->andReturn([
        'links' => [
            ['rel' => 'self', 'href' => 'http://localhost/self'],
        ]
    ]);

    app()->instance(\Srmklive\PayPal\Services\PayPal::class, $mock);
}


function mockPaypalCompleted()
{
    $mockProvider = m::mock(\Srmklive\PayPal\Services\PayPal::class);

    $mockProvider->shouldReceive('setApiCredentials')->andReturnSelf();
    $mockProvider->shouldReceive('getAccessToken')->andReturn('fake-access-token');
    $mockProvider->shouldReceive('capturePaymentOrder')
        ->with('123')
        ->andReturn(['status' => 'COMPLETED']);

    app()->instance(\Srmklive\PayPal\Services\PayPal::class, $mockProvider);
}


function mockPaypalNotCompleted()
{
    $mockProvider = m::mock(\Srmklive\PayPal\Services\PayPal::class);

    $mockProvider->shouldReceive('setApiCredentials')->andReturnSelf();
    $mockProvider->shouldReceive('getAccessToken')->andReturn('fake-access-token');
    $mockProvider->shouldReceive('capturePaymentOrder')
        ->with('123')
        ->andReturn(['status' => 'FAILED']);

    app()->instance(\Srmklive\PayPal\Services\PayPal::class, $mockProvider);
}

