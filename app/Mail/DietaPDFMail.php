<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade\Pdf;

class DietaPDFMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $pdf;
    public $dia;

    public function __construct($user, $dia)
    {
        $this->user = $user;
        $this->dia = $dia;
    }




    public function build()
    {
        // ⚠️ Asumimos que la dieta se obtiene desde el usuario directamente
        $dieta = json_decode($this->user->dieta->dieta, true);

        $pdf = Pdf::loadView('exports.dieta-semanal', [
            'dieta' => $dieta,
        ])->output();

        return $this->subject("Tu dieta semanal")
            ->markdown('emails.dieta.pdf')
            ->attachData($pdf, "dieta_{$this->dia}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }

}
