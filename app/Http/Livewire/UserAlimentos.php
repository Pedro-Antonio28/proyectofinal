<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Alimento;
use Illuminate\Support\Facades\Auth;

class UserAlimentos extends Component
{
    public $alimentos;
    public $favoritos = [];

    public function mount()
    {
        $this->alimentos = Alimento::all();
        $this->favoritos = Auth::user()->alimentosFavoritos->pluck('id')->toArray();
    }

    public function render()
    {
        return view('livewire.user-alimentos')->layout('layouts.livewireLayout');
    }





    public function guardarSeleccion()
    {
        $user = Auth::user();
        $user->alimentosFavoritos()->sync($this->favoritos);

        // Redirigir al dashboard después de guardar
        return redirect()->route('dashboard')->with('message', 'Alimentos guardados correctamente.');
    }

}
