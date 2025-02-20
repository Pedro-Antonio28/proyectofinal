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

        // Configurar Carbon para que devuelva el día en español correctamente
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $this->diaActual = ucfirst(Carbon::now()->translatedFormat('l'));
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
