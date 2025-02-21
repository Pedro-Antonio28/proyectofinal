<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Alimento;
use Illuminate\Support\Facades\Session;

class AgregarAlimento extends Component
{
public $dia;
public $tipoComida;
public $alimentoSeleccionado;
public $cantidad = 100;
public $alimentos;

public function mount($dia, $tipoComida)
{
$this->dia = $dia;
$this->tipoComida = $tipoComida;
$this->alimentos = Alimento::all();
}

    public function agregarAlimento()
    {
        if (!$this->alimentoSeleccionado) return;

        $alimento = Alimento::find($this->alimentoSeleccionado);

        $nuevoAlimento = [
            'nombre' => $alimento->nombre,
            'cantidad' => $this->cantidad,
            'calorias' => $alimento->calorias,
            'proteinas' => $alimento->proteinas,
            'carbohidratos' => $alimento->carbohidratos,
            'grasas' => $alimento->grasas,
        ];

        $dieta = session("dieta_semanal.{$this->dia}", []);
        $dieta[$this->tipoComida][] = $nuevoAlimento;
        session(["dieta_semanal.{$this->dia}" => $dieta]);
        session()->save(); // 🔥 Guardar sesión

        // 🔹 Emitir un evento para que Livewire detecte el cambio sin recargar la página
        $this->emit('dietaActualizada');

        return redirect()->route('dashboard')->with('message', 'Alimento añadido correctamente.');
    }



    public function render()
{
return view('livewire.agregar-alimento')->layout('layouts.livewireLayout');
}
}
