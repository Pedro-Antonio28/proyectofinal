<?php

use App\Events\UserDeleted;
use App\Listeners\LogUserDeleted;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registers the UserDeleted event with LogUserDeleted listener', function () {
    Event::fake();
    $this->app->register(\App\Providers\EventServiceProvider::class);

    event(new UserDeleted(User::factory()->create()));

    Event::assertListening(UserDeleted::class, LogUserDeleted::class);
});
