<?php

namespace App\Jobs;

use App\Mail\AlertaCambioPassword;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class EnviarAlertaCambioPassword
{
    use Dispatchable, Queueable;

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new AlertaCambioPassword($this->user));
    }
}
