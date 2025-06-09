<?php

use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it has fillable attributes', function () {
    $telegramUser = new TelegramUser();

    expect($telegramUser->getFillable())->toBe(['user_id', 'telegram_id']);
});

test('it belongs to a user', function () {
    $user = User::factory()->create();
    $telegramUser = TelegramUser::create([
        'user_id' => $user->id,
        'telegram_id' => '123456789',
    ]);

    expect($telegramUser->user)->toBeInstanceOf(User::class);
    expect($telegramUser->user->id)->toBe($user->id);
});
