<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alimento;

class AlimentoController extends Controller
{
    public function index()
    {
        $alimentos = Alimento::all();
        return view('alimentos.index', compact('alimentos'));
    }

    public function create()
    {
        return view('alimentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validar la imagen
            'calorias' => 'required|integer|min:0',
            'proteinas' => 'required|integer|min:0',
            'carbohidratos' => 'required|integer|min:0',
            'grasas' => 'required|integer|min:0',
        ]);

        $imagenPath = null;

        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('alimentos', 'public'); // Guardar en `storage/app/public/alimentos`
        }

        Alimento::create([
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'imagen' => $imagenPath,
            'calorias' => $request->calorias,
            'proteinas' => $request->proteinas,
            'carbohidratos' => $request->carbohidratos,
            'grasas' => $request->grasas,
        ]);

        return redirect()->route('alimentos.index')->with('success', 'Alimento agregado correctamente.');
    }

    public function update(Request $request, Alimento $alimento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'calorias' => 'required|integer|min:0',
            'proteinas' => 'required|integer|min:0',
            'carbohidratos' => 'required|integer|min:0',
            'grasas' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('alimentos', 'public');
            $alimento->imagen = $imagenPath;
        }

        $alimento->update($request->except('imagen') + ['imagen' => $alimento->imagen]);

        return redirect()->route('alimentos.index')->with('success', 'Alimento actualizado correctamente.');
    }


    public function edit(Alimento $alimento)
    {
        return view('alimentos.edit', compact('alimento'));
    }


    public function destroy(Alimento $alimento)
    {
        $alimento->delete();
        return redirect()->route('alimentos.index')->with('success', 'Alimento eliminado correctamente.');
    }
}
