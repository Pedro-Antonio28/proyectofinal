<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Alimento;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgregarAlimento extends Component
{
    public $dia;
    public $tipoComida;
    public $nombre;
    public $calorias;
    public $proteinas;
    public $carbohidratos;
    public $grasas;

    public function mount($dia, $tipoComida)
    {
        $this->dia = $dia;
        $this->tipoComida = $tipoComida;
    }

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'calorias' => 'required|numeric|min:0',
            'proteinas' => 'required|numeric|min:0',
            'carbohidratos' => 'required|numeric|min:0',
            'grasas' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        // ✅ Buscar la dieta del usuario o crear una si no existe
        $dieta = Dieta::firstOrCreate([
            'user_id' => $user->id,
            'semana' => Carbon::now()->weekOfYear
        ]);

        // ✅ Crear el alimento en la base de datos
        $alimento = Alimento::create([
            'nombre' => $this->nombre,
            'calorias' => $this->calorias,
            'proteinas' => $this->proteinas,
            'carbohidratos' => $this->carbohidratos,
            'grasas' => $this->grasas,
        ]);

        // ✅ Asociar el alimento a la dieta del usuario
        DietaAlimento::create([
            'dieta_id' => $dieta->id,
            'alimento_id' => $alimento->id,
            'dia' => $this->dia,
            'tipo_comida' => $this->tipoComida,
            'cantidad' => 100, // Valor por defecto
            'consumido' => false,
        ]);

        session()->flash('message', '✅ Alimento añadido con éxito.');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.agregar-alimento')->layout('layouts.livewireLayout');
    }
}
