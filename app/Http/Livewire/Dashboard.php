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

        // 🗓️ Obtener la semana actual (Ejemplo: "2024-W08")
        $semanaActual = now()->format('o-W');

        // 🔹 Comprobar si ya hay una dieta generada en la sesión para esta semana
        if (!session()->has("dieta_semana_{$semanaActual}")) {
            // ⚡ Generar la dieta solo si no está en la sesión
            $this->dieta = $this->dietaService->generarDietaSemanal($user);

            // 🏷️ Guardar en la sesión para que no cambie hasta la próxima semana
            session(["dieta_semana_{$semanaActual}" => $this->dieta]);
        } else {
            // 🔄 Recuperar la dieta guardada en la sesión
            $this->dieta = session("dieta_semana_{$semanaActual}");
        }

        // 🗓️ Determinar el día actual en español
        $dias = [
            'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'
        ];
        $diaIngles = now()->format('l');
        $this->diaActual = $dias[$diaIngles] ?? 'Lunes';

        // 🔹 Verificar si el día actual es el mismo día del sistema
        $hoy = now()->locale('es')->isoFormat('dddd');
        $this->esDiaActual = (ucfirst($this->diaActual) === ucfirst($hoy));

        // 🔹 Cargar los alimentos seleccionados en la sesión
        $this->alimentosConsumidos = session("alimentos_consumidos.{$this->diaActual}", []);

        if (!is_array($this->alimentosConsumidos)) {
            logger()->error("⚠ ERROR en mount(): `alimentosConsumidos` NO es un array, tipo detectado: " . gettype($this->alimentosConsumidos));
            $this->alimentosConsumidos = [];
        }

        // 🚀 Registrar estado inicial
        logger()->info("🔄 Montando Dashboard con dieta semanal", [
            'Semana' => $semanaActual,
            'Día actual' => $this->diaActual,
            'Hoy es' => ucfirst($hoy),
            'Dieta fija' => session("dieta_semana_{$semanaActual}"),
            'esDiaActual' => $this->esDiaActual,
            'alimentosConsumidos' => $this->alimentosConsumidos
        ]);
    }






    public function cambiarDia($dia)
    {
        $this->diaActual = $dia;

        // 🔹 Verificar si es el día actual
        $hoy = Carbon::now()->locale('es')->isoFormat('dddd');
        $this->esDiaActual = (ucfirst($this->diaActual) === ucfirst($hoy));

        // 🔹 Cargar alimentos desde la sesión si existen, de lo contrario, forzar array vacío
        $alimentosGuardados = session("alimentos_consumidos.{$this->diaActual}");

        if (is_null($alimentosGuardados)) {
            logger()->info("⚠ No hay alimentos guardados en la sesión para {$this->diaActual}, manteniendo los actuales.");
            return; // No sobreescribas, mantenemos lo anterior
        }

        if (is_array($alimentosGuardados)) {
            $this->alimentosConsumidos = $alimentosGuardados;
        } else {
            logger()->error("❌ ERROR en cambiarDia(): `alimentosConsumidos` no es un array, sino un: " . gettype($alimentosGuardados));
            $this->alimentosConsumidos = [];
        }


        // 🚀 Registrar en el log para verificar qué está pasando
        logger()->info("📅 Día cambiado a: {$this->diaActual}", [
            'Hoy es' => ucfirst($hoy),
            'esDiaActual' => $this->esDiaActual,
            'alimentosConsumidos' => json_encode($this->alimentosConsumidos)
        ]);

        // 🔹 Forzar actualización visual de los checkboxes
        $this->js('window.dispatchEvent(new Event("refreshCheckboxes"))');

    }

    public function toggleAlimento($alimento)
    {
        if (!$this->esDiaActual) {
            logger()->warning("❌ Intento de marcar un alimento en un día NO actual: {$this->diaActual}");
            return;
        }

        // Si `alimentosConsumidos` no es un array, lo inicializamos
        if (!is_array($this->alimentosConsumidos)) {
            $this->alimentosConsumidos = [];
        }

        if (in_array($alimento, $this->alimentosConsumidos)) {
            $this->alimentosConsumidos = array_values(array_diff($this->alimentosConsumidos, [$alimento]));
        } else {
            $this->alimentosConsumidos[] = $alimento;
        }

        // ✅ Guardar en la sesión asegurando que es un array
        session(["alimentos_consumidos.{$this->diaActual}" => array_values($this->alimentosConsumidos)]);
        session()->save();

        // 🚀 Registrar cambios en el log
        logger()->info("✅ Alimento actualizado: {$alimento}", [
            'Día actual' => $this->diaActual,
            'esDiaActual' => $this->esDiaActual,
            'alimentosConsumidos' => json_encode($this->alimentosConsumidos)
        ]);

        // 🔹 Forzar actualización visual en los checkboxes
        $this->dispatch('refreshCheckboxes');

    }





    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.livewireLayout');
    }
}
