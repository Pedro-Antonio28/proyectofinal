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
    public $dietaFormateada = [];

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
    }


    /**
     * Formatear la dieta para que la vista pueda mostrarla correctamente
     */
    private function formatearDieta($dieta)
    {
        $dietaFormateada = [];

        foreach ($dieta->alimentos as $alimento) {
            $tipoComida = $alimento->tipo_comida;

            $dietaFormateada[$tipoComida][] = [
                'id' => $alimento->alimento->id,
                'nombre' => $alimento->alimento->nombre,
                'cantidad' => $alimento->cantidad,
                'calorias' => $alimento->calorias,
                'proteinas' => $alimento->proteinas,
                'carbohidratos' => $alimento->carbohidratos,
                'grasas' => $alimento->grasas,
            ];
        }

        return $dietaFormateada;
    }




    public function cambiarDia($dia)
    {
        $this->diaActual = $dia;
        $hoy = Carbon::now()->locale('es')->isoFormat('dddd');
        $this->esDiaActual = (ucfirst($this->diaActual) === ucfirst($hoy));

        if (!$this->dieta) {
            return;
        }

        // 🔹 Cargar alimentos consumidos desde la base de datos
        $this->alimentosConsumidos = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->where('dia', $this->diaActual)
            ->where('consumido', true)
            ->pluck('alimento_id')
            ->toArray();

        // 🔄 Refrescar checkboxes
        $this->dispatch('refreshCheckboxes');
    }

    public function toggleAlimento($alimentoId)
    {
        if (!$this->esDiaActual || !$this->dieta) {
            return;
        }

        // ✅ Buscar el alimento en la base de datos
        $dietaAlimento = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->where('dia', $this->diaActual)
            ->where('alimento_id', $alimentoId)
            ->first();

        if ($dietaAlimento) {
            // 🔹 Alternar el estado de `consumido`
            $dietaAlimento->consumido = !$dietaAlimento->consumido;
            $dietaAlimento->save();
        }

        // ✅ Volver a cargar los alimentos seleccionados
        $this->alimentosConsumidos = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->where('dia', $this->diaActual)
            ->where('consumido', true)
            ->pluck('alimento_id')
            ->toArray();

        // 🔄 Refrescar checkboxes
        $this->dispatch('refreshCheckboxes');
    }

    public function render()
    {
        if (!$this->dieta) {
            return view('livewire.dashboard', ['comidas' => []])->layout('layouts.livewireLayout');
        }

        $comidas = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->where('dia', $this->diaActual)
            ->get()
            ->groupBy('tipo_comida');

        return view('livewire.dashboard', compact('comidas'))->layout('layouts.livewireLayout');
    }
}

