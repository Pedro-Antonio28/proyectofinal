<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Alimento;
use Illuminate\Http\Request;

class ApiAlimentoController extends Controller
{
    public function index()
    {
        return response()->json(Alimento::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'calorias' => 'required|numeric|min:0',
            'proteinas' => 'required|numeric|min:0',
            'carbohidratos' => 'required|numeric|min:0',
            'grasas' => 'required|numeric|min:0',
        ]);

        $alimento = Alimento::create($request->all());

        return response()->json([
            'message' => 'Alimento creado con éxito',
            'alimento' => $alimento
        ], 201);
    }

    public function show($id)
    {
        $alimento = Alimento::find($id);

        if (!$alimento) {
            return response()->json(['message' => 'Alimento no encontrado'], 404);
        }

        return response()->json($alimento, 200);
    }

    public function update(Request $request, $id)
    {
        $alimento = Alimento::find($id);

        if (!$alimento) {
            return response()->json(['message' => 'Alimento no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'calorias' => 'sometimes|numeric|min:0',
            'proteinas' => 'sometimes|numeric|min:0',
            'carbohidratos' => 'sometimes|numeric|min:0',
            'grasas' => 'sometimes|numeric|min:0',
        ]);

        $alimento->update($request->all());

        return response()->json([
            'message' => 'Alimento actualizado correctamente',
            'alimento' => $alimento
        ], 200);
    }

    public function destroy($id)
    {
        $alimento = Alimento::find($id);

        if (!$alimento) {
            return response()->json(['message' => 'Alimento no encontrado'], 404);
        }

        $alimento->delete();

        return response()->json(['message' => 'Alimento eliminado correctamente'], 200);
    }
}
