<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DietaDiariaExport implements FromView
{
    public function __construct(public array $comidas, public string $dia) {}

    public function view(): View
    {
        return view('exports.dieta-diaria', [
            'comidas' => $this->comidas,
            'dia' => $this->dia,
        ]);
    }
}
