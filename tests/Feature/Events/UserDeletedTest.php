<?php

use App\Events\UserDeleted;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('dispatches UserDeleted event', function () {
    Event::fake();

    $user = User::factory()->create();
    event(new UserDeleted($user));

    Event::assertDispatched(UserDeleted::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });
});
