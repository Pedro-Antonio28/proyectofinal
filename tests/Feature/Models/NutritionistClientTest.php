<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a nutritionist-client relationship', function () {
    $nutritionist = User::factory()->create();
    $client = User::factory()->create();

    $nutritionist->clientes()->attach($client);

    expect($nutritionist->clientes)->toHaveCount(1);
    expect($nutritionist->clientes->first()->id)->toBe($client->id);
});

it('can retrieve clients for a nutritionist', function () {
    $nutritionist = User::factory()->create();
    $client = User::factory()->create();

    $nutritionist->clientes()->attach($client);

    $retrievedClients = $nutritionist->clientes()->get();

    expect($retrievedClients)->toHaveCount(1);
    expect($retrievedClients->first()->id)->toBe($client->id);
});
