<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\DietaService;
use Carbon\Carbon;

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
        $this->diaActual = $dias[$diaIngles] ?? 'Día desconocido';
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
