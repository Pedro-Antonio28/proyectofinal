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
    public $comidas = [];

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

        $this->determinarDiaActual();
        $this->cargarDietaDelDia();
    }

    public function determinarDiaActual()
    {
        $dias = [
            'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'
        ];
        $this->diaActual = $dias[now()->format('l')] ?? 'Lunes';

        $hoy = Carbon::now()->locale('es')->isoFormat('dddd');
        $this->esDiaActual = (ucfirst($this->diaActual) === ucfirst($hoy));
    }

    public function cargarDietaDelDia()
    {
        if (!$this->dieta) return;

        $comidasCollection = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->where('dia', $this->diaActual)
            ->with('alimento')
            ->get()
            ->groupBy('tipo_comida');

        $this->comidas = $comidasCollection->map(function ($items) {
            return $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nombre' => $item->alimento->nombre,
                    'cantidad' => $item->cantidad,
                    'calorias' => $item->alimento->calorias,
                    'proteinas' => $item->alimento->proteinas,
                    'carbohidratos' => $item->alimento->carbohidratos,
                    'grasas' => $item->alimento->grasas,
                    'alimento_id' => $item->alimento_id,
                ];
            })->toArray();
        })->toArray();

        $this->actualizarMacrosConsumidos();
    }

    public function actualizarMacrosConsumidos()
    {
        if (!$this->dieta) return;

        $this->caloriasConsumidas = 0;
        $this->proteinasConsumidas = 0;
        $this->carbohidratosConsumidos = 0;
        $this->grasasConsumidas = 0;

        $this->alimentosConsumidos = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->where('dia', $this->diaActual)
            ->where('consumido', true)
            ->pluck('alimento_id')
            ->toArray();

        foreach ($this->comidas as $tipoComida => $alimentos) {
            foreach ($alimentos as $comida) {
                if (in_array($comida['alimento_id'], $this->alimentosConsumidos)) {
                    $this->caloriasConsumidas += $comida['calorias'];
                    $this->proteinasConsumidas += $comida['proteinas'];
                    $this->carbohidratosConsumidos += $comida['carbohidratos'];
                    $this->grasasConsumidas += $comida['grasas'];
                }
            }
        }
    }

    public function cambiarDia($dia)
    {
        $this->diaActual = $dia;
        $this->esDiaActual = ucfirst($this->diaActual) === ucfirst(Carbon::now()->locale('es')->isoFormat('dddd'));
        $this->cargarDietaDelDia();
        $this->dispatch('refreshUI'); // 🔄 Forzar actualización en Livewire
    }

    public function toggleAlimento($alimentoId)
    {
        if (!$this->esDiaActual || !$this->dieta) {
            return;
        }

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

    public function render()
    {
        $user = Auth::user();

        return view('livewire.dashboard', [
            'comidas' => $this->comidas,
            'caloriasTotales' => $user->calorias_necesarias,
            'proteinasTotales' => $user->proteinas,
            'carbohidratosTotales' => $user->carbohidratos,
            'grasasTotales' => $user->grasas,
            'esDiaActual' => $this->esDiaActual,
        ])->layout('layouts.livewireLayout');
    }
}
