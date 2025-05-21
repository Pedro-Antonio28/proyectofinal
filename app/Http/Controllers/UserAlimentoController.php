<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alimento;
use Illuminate\Support\Facades\Auth;

class UserAlimentoController extends Controller
{
    public function index()
    {
        $alimentos = Alimento::all();
        $favoritos = Auth::user()->alimentosFavoritos->pluck('id')->toArray();

        return view('user.alimentos', compact('alimentos', 'favoritos'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $alimentosSeleccionados = $request->input('alimentos', []);
        $alimentos = Alimento::whereIn('id', $alimentosSeleccionados)->get();

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
            return redirect()->back()->withErrors($errores)->withInput();
        }

        $user->alimentosFavoritos()->sync($alimentosSeleccionados);

        return redirect()->route('dashboard')->with('message', 'Alimentos guardados correctamente.');
    }
}
