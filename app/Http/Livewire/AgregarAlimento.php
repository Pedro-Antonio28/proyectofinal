<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Alimento;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\StoreAlimentoRequest;
use Illuminate\Validation\ValidationException;

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
        try {
            // Validación inicial
            $this->validate([
                'nombre' => 'required|string|max:255',
                'calorias' => 'required|numeric|min:0|max:1000',
                'proteinas' => 'required|numeric|min:0|max:500',
                'carbohidratos' => 'required|numeric|min:0|max:500',
                'grasas' => 'required|numeric|min:0|max:100',
            ]);

            $categoria = 'otro';
            $imagen = null;

            // Validación extra usando StoreAlimentoRequest
            $request = new \App\Http\Requests\StoreAlimentoRequest();
            $validatedData = $this->validate($request->rules());

            $user = auth()->user();

            // Buscar o crear el alimento
            $alimento = \App\Models\Alimento::firstOrCreate([
                'nombre' => $validatedData['nombre']
            ], [
                'categoria' => $categoria,
                'calorias' => $validatedData['calorias'],
                'proteinas' => $validatedData['proteinas'],
                'carbohidratos' => $validatedData['carbohidratos'],
                'grasas' => $validatedData['grasas'],
            ]);

            // Buscar o crear la dieta del usuario para la semana actual
            $dieta = \App\Models\Dieta::firstOrCreate([
                'user_id' => $user->id,
                'semana' => \Carbon\Carbon::now()->weekOfYear,
            ]);

            // Crear el registro en DietaAlimento
            \App\Models\DietaAlimento::create([
                'dieta_id' => $dieta->id,
                'alimento_id' => $alimento->id,
                'dia' => $this->dia,
                'tipo_comida' => $this->tipoComida,
                'cantidad' => 100, // Valor predeterminado
                'consumido' => false,
            ]);

            // Actualizar el campo JSON en la dieta
            $dietaJson = json_decode($dieta->dieta, true);
            if (!$dietaJson) {
                $dietaJson = [];
            }
            // Asegurar que existe la clave del día
            if (!isset($dietaJson[$this->dia])) {
                $dietaJson[$this->dia] = [];
            }
            // Asegurar que existe la clave del tipo de comida
            if (!isset($dietaJson[$this->dia][$this->tipoComida])) {
                $dietaJson[$this->dia][$this->tipoComida] = [];
            }
            // Agregar el nuevo alimento al array correspondiente
            $dietaJson[$this->dia][$this->tipoComida][] = [
                'alimento_id' => $alimento->id,
                'nombre' => $alimento->nombre,
                'calorias' => $alimento->calorias,
                'proteinas' => $alimento->proteinas,
                'carbohidratos' => $alimento->carbohidratos,
                'grasas' => $alimento->grasas,
                'cantidad' => 100,
                'consumido' => false,
            ];

            $dieta->dieta = json_encode($dietaJson);
            $dieta->save();

            session()->flash('message', '✅ Alimento añadido con éxito.');

            // Redirigir al dashboard para refrescar la UI
            return redirect()->route('dashboard');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('validation', '❌ Error: Hay campos inválidos o vacíos.');
            session()->flash('error', '❌ Verifica los campos antes de continuar.');
        }
    }






    public function render()
    {
        return view('livewire.agregar-alimento')->layout('layouts.livewireLayout');
    }
}
