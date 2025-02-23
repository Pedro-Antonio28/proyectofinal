<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Crear una nueva instancia del Job.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Ejecutar el Job.
     */


    public function handle()
    {
        \Log::info('Ejecutando SendVerificationEmail para: ' . $this->user->email);

        // ✅ Generar una URL firmada válida por 60 minutos
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $this->user->id]
        );

        $user = $this->user;
        \Log::info('Enviando email a: ' . $user->email);
        Mail::send('emails.verify', ['url' => $verificationUrl, 'user' => $user], function ($message) use ($user) {
            $message->to($user->email)->subject('Verifica tu correo - Calorix');
        });
    }

}
