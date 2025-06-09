<?php

use App\Models\User;
use App\Events\DietaSolicitada;

test('DietaSolicitada event carries correct user', function () {
    $user = User::factory()->create();

    $event = new DietaSolicitada($user);

    expect($event->user->id)->toBe($user->id);
});
