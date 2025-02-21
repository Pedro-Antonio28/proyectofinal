<?php

namespace App\Http\Livewire;

use App\Services\DietaService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $dieta;
    public $diaActual;
    public $esDiaActual;
    public $alimentosConsumidos = [];
    public $caloriasConsumidas = 0;
    public $proteinasConsumidas = 0;
    public $carbohidratosConsumidos = 0;
    public $grasasConsumidas = 0;

    public function mount()
    {
        $user = Auth::user();
        $semanaActual = Carbon::now()->weekOfYear;

        $this->dieta = Dieta::where('user_id', $user->id)
            ->where('semana', $semanaActual)
            ->with('alimentos.alimento')
            ->first();

        if (!$this->dieta) {
            $dietaService = new DietaService();
            $this->dieta = $dietaService->generarDietaSemanal($user);
        }

        // 🗓️ Determinar el día actual
        $dias = [
            'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'
        ];
        $this->diaActual = $dias[now()->format('l')] ?? 'Lunes';

        $this->actualizarMacrosConsumidos();
    }

    public function actualizarMacrosConsumidos()
    {
        $this->caloriasConsumidas = 0;
        $this->proteinasConsumidas = 0;
        $this->carbohidratosConsumidos = 0;
        $this->grasasConsumidas = 0;

        $alimentosConsumidos = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->where('dia', $this->diaActual)
            ->where('consumido', true)
            ->get();

        foreach ($alimentosConsumidos as $alimento) {
            $this->caloriasConsumidas += $alimento->alimento->calorias;
            $this->proteinasConsumidas += $alimento->alimento->proteinas;
            $this->carbohidratosConsumidos += $alimento->alimento->carbohidratos;
            $this->grasasConsumidas += $alimento->alimento->grasas;
        }
    }

    public function toggleAlimento($alimentoId)
    {
        if (!$this->dieta) return;

        $dietaAlimento = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->where('dia', $this->diaActual)
            ->where('alimento_id', $alimentoId)
            ->first();

        if ($dietaAlimento) {
            $dietaAlimento->consumido = !$dietaAlimento->consumido;
            $dietaAlimento->save();
        }

        $this->actualizarMacrosConsumidos();
    }

    public function cambiarDia($dia)
    {
        $this->diaActual = $dia;
        $this->actualizarMacrosConsumidos();
    }

    public function render()
    {
        $user = Auth::user();

        // ✅ Se añade la variable $comidas para evitar el error
        $comidas = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->where('dia', $this->diaActual)
            ->get()
            ->groupBy('tipo_comida');

        return view('livewire.dashboard', [
            'comidas' => $comidas,
            'caloriasTotales' => $user->calorias_necesarias,
            'proteinasTotales' => $user->proteinas,
            'carbohidratosTotales' => $user->carbohidratos,
            'grasasTotales' => $user->grasas,
        ])->layout('layouts.livewireLayout');
    }
}
