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

    public $dummy = 0;


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
            'Monday'    => 'Lunes',
            'Tuesday'   => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday'  => 'Jueves',
            'Friday'    => 'Viernes',
            'Saturday'  => 'Sábado',
            'Sunday'    => 'Domingo'
        ];
        $this->diaActual = $dias[now()->format('l')] ?? 'Lunes';

        $hoy = Carbon::now()->locale('es')->isoFormat('dddd');
        $this->esDiaActual = (ucfirst($this->diaActual) === ucfirst($hoy));
    }

    public function cargarDietaDelDia()
    {
        if (!$this->dieta) {
            return;
        }

        $dietaJson = json_decode($this->dieta->dieta, true);

        // Asegurar que $diaActual tiene un valor válido
        if (!isset($dietaJson[$this->diaActual])) {
            $this->comidas = [];
        } else {
            $this->comidas = $dietaJson[$this->diaActual];
        }

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

        $alimentos = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->where('dia', $this->diaActual)
            ->with('alimento')
            ->get();

        foreach ($alimentos as $alimento) {
            if (in_array($alimento->alimento_id, $this->alimentosConsumidos)) {
                $cantidadReal = $alimento->cantidad;
                $this->caloriasConsumidas      += ($alimento->alimento->calorias * $cantidadReal) / 100;
                $this->proteinasConsumidas     += ($alimento->alimento->proteinas * $cantidadReal) / 100;
                $this->carbohidratosConsumidos += ($alimento->alimento->carbohidratos * $cantidadReal) / 100;
                $this->grasasConsumidas        += ($alimento->alimento->grasas * $cantidadReal) / 100;
            }
        }
    }

    // Se dispara automáticamente cuando se actualiza la propiedad diaActual
    public function updatedDiaActual()
    {
        $this->esDiaActual = (ucfirst($this->diaActual) === ucfirst(Carbon::now()->locale('es')->isoFormat('dddd')));

        $this->comidas = []; // Vaciar temporalmente para forzar la actualización
        $this->cargarDietaDelDia();

        $this->dummy++; // ⚡ Forzar a Livewire a detectar el cambio
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
        $this->cargarDietaDelDia(); // Asegurar que siempre se carga la dieta correcta
        $user = Auth::user();
        return view('livewire.dashboard', [
            'comidas'              => $this->comidas,
            'caloriasTotales'      => $user->calorias_necesarias,
            'proteinasTotales'     => $user->proteinas,
            'carbohidratosTotales' => $user->carbohidratos,
            'grasasTotales'        => $user->grasas,
            'esDiaActual'          => $this->esDiaActual,
            'alimentosConsumidos'  => $this->alimentosConsumidos,
        ])->layout('layouts.livewireLayout');
    }
}
