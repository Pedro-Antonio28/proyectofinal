<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable; // ← ESTA ES LA CLAVE
use Illuminate\Support\Facades\Mail;
use App\Mail\DietaPDFMail;

class EnviarPDFPorCorreoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $pdf;
    public $dia;

    public function __construct($user, $pdf, $dia)
    {
        $this->user = $user;
        $this->pdf = $pdf;
        $this->dia = $dia;
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new DietaPDFMail($this->user, $this->pdf, $this->dia));
    }
}
