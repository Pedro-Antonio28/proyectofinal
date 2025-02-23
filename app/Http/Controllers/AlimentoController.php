<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlimentoRequest;
use App\Http\Requests\UpdateAlimentoRequest;
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

    public function store(StoreAlimentoRequest $request)
    {
        $imagenPath = null;

        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('alimentos', 'public');
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

    public function update(UpdateAlimentoRequest $request, Alimento $alimento)
    {
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
