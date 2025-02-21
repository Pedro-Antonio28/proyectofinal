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

        $dias = [
            'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'
        ];

        $diaIngles = Carbon::now()->format('l');
        $this->diaActual = $dias[$diaIngles] ?? 'Lunes';

        // 🔹 Asegurar que `esDiaActual` se calcula bien al iniciar el componente
        $hoy = Carbon::now()->locale('es')->isoFormat('dddd');
        $this->esDiaActual = (ucfirst($this->diaActual) === ucfirst($hoy));

        // 🔹 Cargar los alimentos seleccionados en la sesión
        $this->alimentosConsumidos = session("alimentos_consumidos.{$this->diaActual}", []);

        if (!is_array($this->alimentosConsumidos)) {
            logger()->error("⚠ ERROR en mount(): `alimentosConsumidos` NO es un array, tipo detectado: " . gettype($this->alimentosConsumidos));
            $this->alimentosConsumidos = [];
        }

        // 🚀 Registrar el estado inicial
        logger()->info("🔄 Montando componente Dashboard", [
            'Día actual' => $this->diaActual,
            'Hoy es' => ucfirst($hoy),
            'esDiaActual' => $this->esDiaActual,
            'alimentosConsumidos' => $this->alimentosConsumidos
        ]);
    }





    public function cambiarDia($dia)
    {
        $this->diaActual = $dia;

        // 🔹 Obtener el día actual real en español correctamente
        $hoy = Carbon::now()->locale('es')->isoFormat('dddd'); // Esto devuelve el nombre del día en español correctamente

        // 🔹 Comparar con `ucfirst()` para que coincidan exactamente
        $this->esDiaActual = (ucfirst($this->diaActual) === ucfirst($hoy));

        // 🔹 Cargar alimentos del día seleccionado
        $this->alimentosConsumidos = session("alimentos_consumidos.{$this->diaActual}", []);

        // 🔹 Si la sesión devuelve `null` o `string`, forzamos un array vacío
        if (!is_array($this->alimentosConsumidos)) {
            $this->alimentosConsumidos = [];
        }

        // 🚀 Registrar en el log para verificar si ahora funciona bien
        logger()->info("📅 Día cambiado a: {$this->diaActual}", [
            'Hoy es' => ucfirst($hoy),
            'esDiaActual' => $this->esDiaActual,
            'alimentosConsumidos' => $this->alimentosConsumidos
        ]);
    }





    public function toggleAlimento($alimento)
    {
        if (!$this->esDiaActual) {
            logger()->warning("❌ Intento de marcar un alimento en un día NO actual: {$this->diaActual}");
            return;
        }

        // 🚀 Asegurar que `alimentosConsumidos` es un array antes de modificarlo
        if (!is_array($this->alimentosConsumidos)) {
            logger()->error("⚠ ERROR en toggleAlimento(): `alimentosConsumidos` NO es un array, tipo detectado: " . gettype($this->alimentosConsumidos));
            $this->alimentosConsumidos = [];
        }

        if (in_array($alimento, $this->alimentosConsumidos)) {
            $this->alimentosConsumidos = array_values(array_diff((array) $this->alimentosConsumidos, [$alimento]) ?: []);
        } else {
            $this->alimentosConsumidos[] = $alimento;
        }

        // 🚀 Registrar qué estamos guardando antes de almacenarlo en la sesión
        logger()->info("✅ Alimento actualizado: {$alimento}", [
            'Día actual' => $this->diaActual,
            'esDiaActual' => $this->esDiaActual,
            'alimentosConsumidos' => $this->alimentosConsumidos
        ]);

        // ✅ Guardar en la sesión asegurando que es un array
        session(["alimentos_consumidos.{$this->diaActual}" => array_values((array) $this->alimentosConsumidos)]);
    }







    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.livewireLayout');
    }
}
