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
        $user->alimentosFavoritos()->sync($request->input('alimentos', [])); // Guardar selección

        return redirect()->route('user.alimentos')->with('message', 'Alimentos guardados correctamente.');
    }
}
