<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\DietaService;

class Dashboard extends Component
{
    public $dieta;
    public $diaActual;
    protected $dietaService;

    public function __construct()
    {
        $this->dietaService = new DietaService();
    }

    public function mount()
    {
        $user = Auth::user();
        $this->dieta = $this->dietaService->generarDietaSemanal($user);

        $dias = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];

        $diaIngles = Carbon::now()->format('l');
        $this->diaActual = $dias[$diaIngles] ?? 'Lunes'; // Día actual o por defecto Lunes
    }

    public function cambiarDia($dia)
    {
        $this->diaActual = $dia;
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.livewireLayout');
    }
}

