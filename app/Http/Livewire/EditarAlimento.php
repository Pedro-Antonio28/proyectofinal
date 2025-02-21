<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Alimento;

class EditarAlimento extends Component
{
    public $alimento;
    public $cantidad;
    public $dia;

    public function mount($dia, $nombre)
    {
        $this->dia = $dia;
        $nombre = urldecode($nombre); // 🔥 Decodificar caracteres especiales

        // ✅ Obtener la dieta desde la sesión
        $dieta = session("dieta_semanal.{$this->dia}", []);

        foreach ($dieta as $tipoComida => $comidas) {
            foreach ($comidas as $index => $comida) {
                if ($comida['nombre'] === $nombre) {
                    $this->alimento = [
                        'index' => $index,
                        'tipoComida' => $tipoComida,
                        'nombre' => $comida['nombre'],
                        'cantidad' => $comida['cantidad'],
                        'calorias' => $comida['calorias'],
                        'proteinas' => $comida['proteinas'],
                        'carbohidratos' => $comida['carbohidratos'],
                        'grasas' => $comida['grasas'],
                    ];
                    $this->cantidad = $comida['cantidad'];
                    return;
                }
            }
        }

        // 🚨 Evita mostrar error si el alimento no se encuentra
        abort(404, "El alimento '{$nombre}' no fue encontrado en la dieta.");
    }





    public function actualizarAlimento()
    {
        if (!$this->alimentoSeleccionado) return;

        $dieta = session("dieta_semanal.{$this->dia}", []);

        foreach ($dieta[$this->tipoComida] as &$item) {
            if ($item['nombre'] === $this->alimentoSeleccionado->nombre) {
                $item['cantidad'] = $this->cantidad;
                $item['calorias'] = ($this->cantidad / 100) * $this->alimentoSeleccionado->calorias;
                $item['proteinas'] = ($this->cantidad / 100) * $this->alimentoSeleccionado->proteinas;
                $item['carbohidratos'] = ($this->cantidad / 100) * $this->alimentoSeleccionado->carbohidratos;
                $item['grasas'] = ($this->cantidad / 100) * $this->alimentoSeleccionado->grasas;
            }
        }

        session(["dieta_semanal.{$this->dia}" => $dieta]);
        session()->save();

        return redirect()->route('dashboard')->with('message', 'Alimento actualizado correctamente.');
    }




    public function eliminarAlimento()
    {
        $dieta = session("dieta_semanal.{$this->dia}", []);

        $dieta[$this->tipoComida] = array_filter($dieta[$this->tipoComida], function ($item) {
            return $item['nombre'] !== $this->alimentoSeleccionado->nombre;
        });

        session(["dieta_semanal.{$this->dia}" => $dieta]);
        session()->save();

        return redirect()->route('dashboard')->with('message', 'Alimento eliminado correctamente.');
    }




    public function render()
    {
        return view('livewire.editar-alimento')->layout('layouts.livewireLayout');
    }

}
