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

        $alimentos = Alimento::whereIn('id', $this->favoritos)->get();

        $conteo = [
            'proteinas' => 0,
            'carbohidratos' => 0,
            'verduras' => 0,
            'frutas' => 0,
            'grasas' => 0,
        ];

        $mapaCategorias = [
            'proteinas' => ['proteina', 'proteinas'],
            'carbohidratos' => ['carbohidrato', 'carbohidratos'],
            'verduras' => ['verdura', 'verduras'],
            'frutas' => ['fruta', 'frutas'],
            'grasas' => ['grasa', 'grasas'],
        ];

        foreach ($alimentos as $alimento) {
            $catNormalizada = strtolower(trim($alimento->categoria));
            $catNormalizada = str_replace(['á','é','í','ó','ú','ñ'], ['a','e','i','o','u','n'], $catNormalizada);

            foreach ($mapaCategorias as $clave => $variantes) {
                if (in_array($catNormalizada, $variantes)) {
                    $conteo[$clave]++;
                }
            }
        }

        $errores = [];

        if ($conteo['proteinas'] < 6) $errores[] = 'Debes seleccionar al menos 6 alimentos de la categoría proteínas.';
        if ($conteo['carbohidratos'] < 4) $errores[] = 'Debes seleccionar al menos 4 alimentos de la categoría carbohidratos.';
        if ($conteo['verduras'] < 3) $errores[] = 'Debes seleccionar al menos 3 alimentos de la categoría verduras.';
        if ($conteo['frutas'] < 3) $errores[] = 'Debes seleccionar al menos 3 alimentos de la categoría frutas.';
        if ($conteo['grasas'] < 2) $errores[] = 'Debes seleccionar al menos 2 alimentos de la categoría grasas.';

        if (!empty($errores)) {
            foreach ($errores as $error) {
                $this->addError('seleccion', $error);
            }
            return;
        }

        $user->alimentosFavoritos()->sync($this->favoritos);

        session()->flash('message', 'Alimentos guardados correctamente.');
        Auth::login($user);
        $this->redirect(route('dashboard'));
    }
}
