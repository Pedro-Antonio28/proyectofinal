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
        Auth::user()->alimentosFavoritos()->sync($this->favoritos);
        session()->flash('message', 'Selección guardada correctamente.');
    }
}
