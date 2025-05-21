<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\DietaPDFMail;

class EnviarDietaPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $dietaJson;

    public function __construct($user, $dietaJson)
    {
        $this->user = $user;
        $this->dietaJson = $dietaJson;
    }

    public function handle()
    {
        \Log::info('[Job] Enviando dieta a ' . $this->user->email);

        $pdf = Pdf::loadView('exports.dieta-semanal', [
            'dieta' => $this->dietaJson,
        ])->output();

        Mail::to($this->user->email)->send(new DietaPDFMail($this->user, 'semanal'));


        \Log::info('[Job] PDF enviado con éxito a ' . $this->user->email);
    }

}
