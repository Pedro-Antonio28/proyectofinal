<?php

use App\Events\UserDeleted;
use App\Listeners\LogUserDeleted;
use App\Models\User;
use Illuminate\Support\Facades\Log;

it('logs user deletion when event is fired', function () {
    Log::spy();

    $user = User::factory()->create();
    $event = new UserDeleted($user);
    $listener = new LogUserDeleted();

    $listener->handle($event);

    Log::shouldHaveReceived('info')->once()->with("Usuario eliminado: {$user->id} - {$user->email}");
});
