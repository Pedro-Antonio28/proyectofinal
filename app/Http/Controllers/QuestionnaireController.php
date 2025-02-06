<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    public function show($step)
    {
        // Validamos que el paso esté entre 1 y 6
        if ($step < 1 || $step > 6) {
            return redirect()->route('dashboard');
        }

        return view('questionnaire', compact('step'));
    }

    public function store(Request $request)
    {
        $step = $request->input('step');

        // Definir validaciones
        $rules = [
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:10|max:100',
            'peso' => 'required|numeric|min:30|max:300',
            'altura' => 'required|numeric|min:100|max:250',
            'objetivo' => 'required|string',
            'actividad' => 'required|string',
        ];

        // Obtener el campo actual según el paso
        $fields = ['gender', 'age', 'peso', 'altura', 'objetivo', 'actividad'];
        $field = $fields[$step - 1] ?? null;

        if (!$field) {
            return redirect()->route('dashboard');
        }

        // Validar la respuesta del usuario
        $request->validate([$field => $rules[$field]]);

        // Guardar en sesión temporalmente
        session([$field => $request->input($field)]);

        // Si es la última pregunta, guardar en la base de datos
        if ($step == 6) {
            $user = Auth::user();

            // Guardar cada dato individualmente para asegurarnos de que se guarda bien
            $user->gender = session('gender');
            $user->age = session('age');
            $user->peso = session('peso');
            $user->altura = session('altura');
            $user->objetivo = session('objetivo');
            $user->actividad = session('actividad');
            $user->save();

            // Calcular y guardar las calorías
            $user->actualizarCaloriasYMacros();

            return redirect()->route('user.alimentos')->with('message', 'Cuestionario completado correctamente. Ahora selecciona tus alimentos favoritos.');
        }

        // Redirigir al siguiente paso
        return redirect()->route('questionnaire.show', ['step' => $step + 1]);
    }


}
