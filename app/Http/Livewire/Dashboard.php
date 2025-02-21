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
    public $alimentosConsumidos = [];
    protected $dietaService;

    public $esDiaActual;


    public function __construct()
    {
        $this->dietaService = new DietaService();
    }

    public function mount()
    {
        $user = Auth::user();
        $this->dieta = $this->dietaService->generarDietaSemanal($user);

        $dias = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'];

        $diaIngles = Carbon::now()->format('l');
        $this->diaActual = $dias[$diaIngles] ?? 'Lunes';

        // ✅ Verificar si el día seleccionado es el actual
        $this->esDiaActual = ($this->diaActual === $dias[$diaIngles]);

        if (session()->has('alimentos_consumidos')) {
            $this->alimentosConsumidos = session('alimentos_consumidos');
        }
    }

    public function cambiarDia($dia)
    {
        $this->diaActual = $dia;

        // ✅ Actualizar la variable booleana cuando el usuario cambia de día
        $dias = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'];

        $diaIngles = Carbon::now()->format('l');
        $this->esDiaActual = ($this->diaActual === $dias[$diaIngles]);
    }

    public function toggleAlimento($alimento)
    {
        // Si ya está marcado, lo desmarcamos, si no, lo agregamos
        if (in_array($alimento, $this->alimentosConsumidos)) {
            $this->alimentosConsumidos = array_diff($this->alimentosConsumidos, [$alimento]);
        } else {
            $this->alimentosConsumidos[] = $alimento;
        }

        // Guardar en sesión para persistencia temporal
        session(['alimentos_consumidos' => $this->alimentosConsumidos]);
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.livewireLayout');
    }
}
