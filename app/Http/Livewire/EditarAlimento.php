<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Alimento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\UpdateAlimentoRequest;


class EditarAlimento extends Component
{
    public $alimento;
    public $cantidad;
    public $dia;
    public $tipoComida;

    public function mount($dia, $tipoComida, $alimentoId)
    {
        $this->dia = $dia;
        $this->tipoComida = $tipoComida;

        // ✅ Buscar la dieta del usuario
        $dieta = Dieta::where('user_id', Auth::id())->first();

        if (!$dieta) {
            Session::flash('error', 'No tienes una dieta registrada.');
            return redirect()->route('dashboard');
        }

        // ✅ Buscar el alimento en la dieta con `alimento` relacionado
        $this->alimento = DietaAlimento::where('dieta_id', $dieta->id)
            ->where('dia', $this->dia)
            ->where('tipo_comida', $this->tipoComida)
            ->where('alimento_id', $alimentoId)
            ->with('alimento') // Relación con `alimento`
            ->first();

        // ✅ Verificar si el alimento existe en `dieta_alimentos`
        if (!$this->alimento) {
            Session::flash('error', "El alimento con ID $alimentoId no está en tu dieta.");
            return redirect()->route('dashboard');
        }

        // ✅ Verificar si el alimento existe en `alimentos`
        if (!$this->alimento->alimento) {
            Session::flash('error', 'El alimento seleccionado ya no existe en la base de datos.');
            return redirect()->route('dashboard');
        }

        $this->cantidad = $this->alimento->cantidad;
    }



    public function actualizar()
    {
        try {
            // Validar solo la cantidad, ya que es lo único que editas
            $validatedData = $this->validate([
                'cantidad' => 'required|numeric|min:1',
            ]);

            if ($this->alimento->dieta->user_id !== Auth::id()) {
                session()->flash('error', '❌ No tienes permiso para editar este alimento.');
                return redirect()->route('dashboard');
            }

            // Actualizar la cantidad en la tabla DietaAlimento
            $this->alimento->update($validatedData);

            // Actualizar el campo JSON en la dieta
            $dieta = $this->alimento->dieta;
            $dietaJson = json_decode($dieta->dieta, true);
            if (!$dietaJson) {
                $dietaJson = [];
            }

            // Verificar que existen las claves para el día y el tipo de comida
            if (isset($dietaJson[$this->dia][$this->tipoComida])) {
                foreach ($dietaJson[$this->dia][$this->tipoComida] as &$item) {
                    if ($item['alimento_id'] == $this->alimento->alimento_id) {
                        // Actualizar la cantidad y forzar categoría e imagen
                        $item['cantidad'] = $validatedData['cantidad'];
                        $item['categoria'] = 'otro';
                        $item['imagen'] = null;
                    }
                }
                unset($item);
            }

            $dieta->dieta = json_encode($dietaJson);
            $dieta->save();

            session()->flash('message', '✅ Alimento actualizado con éxito.');
            return redirect()->route('dashboard');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('validation', '❌ Error: Hay campos inválidos o vacíos.');
            session()->flash('error', '❌ Verifica los campos antes de continuar.');
        }
    }


    public function eliminar()
    {
        $this->alimento->delete();
        session()->flash('message', '❌ Alimento eliminado correctamente.');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.editar-alimento')->layout('layouts.livewireLayout');
    }
}
