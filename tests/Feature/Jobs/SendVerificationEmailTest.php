<?php

use App\Jobs\SendVerificationEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can dispatch SendVerificationEmail job', function () {
    Queue::fake();

    $user = User::factory()->create();

    SendVerificationEmail::dispatch($user);

    Queue::assertPushed(SendVerificationEmail::class, function ($job) use ($user) {
        return $job->user->id === $user->id;
    });
});





