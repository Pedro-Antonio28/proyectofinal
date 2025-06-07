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
    public $dietaJson;


    public function __construct($user, $dietaJson, $dia)
    {
        $this->user = $user;
        $this->dietaJson = $dietaJson;
        $this->dia = $dia;

    }





    public function build()
    {
        $pdf = Pdf::loadView('exports.dieta-semanal', [
            'dieta' => $this->dietaJson,
        ])->output();

        return $this->subject("Tu dieta semanal")
            ->markdown('emails.dieta.pdf')
            ->attachData($pdf, "dieta_{$this->dia}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }



}
