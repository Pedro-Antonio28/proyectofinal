<?php

namespace App\Http\Livewire;

use App\Services\DietaService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use Carbon\Carbon;
use App\Exports\DietaDiariaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use App\Jobs\EnviarPDFPorCorreoJob;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\DietaPDFMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\EnviarDietaPdfJob;
use App\Events\DietaSolicitada;

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

    public $mostrarModalTelegram = false;
    public $nuevoTelegramId;

    public function mount()
    {
        $user = Auth::user();
        $semanaActual = Carbon::now()->weekOfYear;

        $this->dieta = Dieta::deSemanaActual($user->id)->first();


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

        $this->alimentosConsumidos = DietaAlimento::consumidos($this->dieta->id, $this->diaActual)
            ->pluck('alimento_id')
            ->toArray();


        $alimentos = DietaAlimento::delDia($this->dieta->id, $this->diaActual)->get();


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

    protected $listeners = ['alimentoAgregado' => 'refreshDieta'];

    public function refreshDieta()
    {
        $this->comidas = DietaAlimento::where('dieta_id', $this->dieta->id)
            ->get()
            ->groupBy('tipo_comida');
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


    public function exportarExcel()
    {
        return Excel::download(
            new DietaDiariaExport($this->comidas, $this->diaActual),
            'dieta_' . $this->diaActual . '.xlsx'
        );
    }


    public function guardarTelegramId()
    {
        $this->validate([
            'nuevoTelegramId' => 'required|numeric',
        ]);

        \App\Models\TelegramUser::updateOrCreate(
            ['user_id' => auth()->id()],
            ['telegram_id' => $this->nuevoTelegramId]
        );

        $this->mostrarModalTelegram = false;

        DietaSolicitada::dispatch(auth()->user());

        session()->flash('success', '✅ Telegram conectado y dieta enviada');
    }

    public function enviarDietaPorTelegram()
    {
        $user = auth()->user();

        if (!$user->telegram) {
            $this->nuevoTelegramId = '';
            $this->mostrarModalTelegram = true;
            return;
        }

        DietaSolicitada::dispatch($user);
        session()->flash('success', '✅ Tu dieta fue enviada por Telegram');
    }


    public function abrirModalTelegram()
    {
        $this->nuevoTelegramId = '';
        $this->mostrarModalTelegram = true;
    }



    public function enviarDietaSemanalPorCorreo()
    {
        \Log::info('✅ Livewire: Método enviarDietaSemanalPorCorreo ejecutado');

        $user = auth()->user();
        $dietaJson = json_decode($this->dieta->dieta, true);

        \App\Jobs\EnviarDietaPdfJob::dispatch($user, $dietaJson);

        session()->flash('success', '📬 Dieta encolada para enviar por correo.');
    }




}
