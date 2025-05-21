<?php

use App\Http\Livewire\WelcomePage;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render the WelcomePage component', function () {
    Livewire::test(WelcomePage::class)
        ->assertStatus(200);
});
