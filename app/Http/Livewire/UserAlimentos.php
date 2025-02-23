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

        if (!$user) {
            session()->flash('error', 'Debes iniciar sesión para continuar.');
            return $this->redirect(route('login'));
        }

        $user->alimentosFavoritos()->sync($this->favoritos);

        session()->flash('message', 'Alimentos guardados correctamente.');

        // ✅ Volver a autenticar al usuario por si se perdió la sesión
        Auth::login($user);

        // ✅ Redirigir correctamente al dashboard
        $this->redirect(route('dashboard'));
    }



}
