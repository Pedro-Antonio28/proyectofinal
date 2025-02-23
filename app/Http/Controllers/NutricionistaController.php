<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use Illuminate\Http\Request;

class NutricionistaController extends Controller
{
    public function index()
    {
        $nutricionista = auth()->user();
        $clientes = $nutricionista->clientes;
        return view('nutricionista.nutricionista_panel', compact('clientes'));

    }

    public function verDieta($id)
    {
        $cliente = User::with('dieta.alimentos.alimento')->findOrFail($id);
        return view('nutricionista.dieta', compact('cliente'));
    }

    public function agregarAlimento(Request $request, $id)
    {
        $request->validate([
            'alimento_id' => 'required|exists:alimentos,id',
            'cantidad' => 'required|numeric|min:1',
            'dia' => 'required|string',
            'tipo_comida' => 'required|string',
        ]);

        DietaAlimento::create([
            'dieta_id' => $id,
            'alimento_id' => $request->input('alimento_id'),
            'cantidad' => $request->input('cantidad'),
            'dia' => $request->input('dia'),
            'tipo_comida' => $request->input('tipo_comida'),
        ]);

        return redirect()->back()->with('success', 'Alimento agregado correctamente.');
    }

    public function editarAlimento(Request $request, $id)
    {
        $request->validate(['cantidad' => 'required|numeric|min:1']);

        $alimento = DietaAlimento::findOrFail($id);
        $alimento->update(['cantidad' => $request->input('cantidad')]);

        return redirect()->back()->with('success', 'Alimento actualizado correctamente.');
    }

    public function eliminarAlimento($id)
    {
        DietaAlimento::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Alimento eliminado correctamente.');
    }
}

