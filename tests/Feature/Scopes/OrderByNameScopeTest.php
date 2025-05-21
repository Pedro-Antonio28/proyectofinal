<?php

use App\Models\User;
use App\Scopes\OrderByNameScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('applies order by name scope', function () {
    User::factory()->create(['name' => 'Carlos']);
    User::factory()->create(['name' => 'Ana']);
    User::factory()->create(['name' => 'Beatriz']);

    $users = User::withoutGlobalScopes()->get();

    expect($users->pluck('name')->toArray())->toBe(['Carlos', 'Ana', 'Beatriz']);

    $users = User::query()->get();

    expect($users->pluck('name')->toArray())->toBe(['Ana', 'Beatriz', 'Carlos']);
});
