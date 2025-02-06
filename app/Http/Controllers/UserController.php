<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function saveQuestionnaire(Request $request)
    {
        // Verificar que los datos llegan correctamente
        \Log::info('Datos recibidos:', $request->all());

        $request->validate([
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:10|max:100',
            'peso' => 'required|numeric|min:30|max:300',
            'altura' => 'required|numeric|min:100|max:250',
            'objetivo' => 'required|string',
            'actividad' => 'required|string',
        ]);

        $user = Auth::user();
        $user->gender = $request->gender;
        $user->age = $request->age;
        $user->peso = $request->peso;
        $user->altura = $request->altura;
        $user->objetivo = $request->objetivo;
        $user->actividad = $request->actividad;
        $user->save();

        return redirect()->route('dashboard')->with('message', 'Cuestionario completado correctamente');
    }



}
