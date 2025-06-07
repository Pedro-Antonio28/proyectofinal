<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Dieta;
use Illuminate\Http\Request;

class ApiDietaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las dietas del usuario autenticado
        return response()->json(
            Dieta::where('user_id', $request->user()->id)->latest()->get(),
            200
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'semana' => 'required|integer|min:1',
            'dieta' => 'nullable|array',
        ]);

        $dieta = Dieta::create([
            'user_id' => $request->user()->id,
            'semana' => $request->input('semana'),
            'dieta' => json_encode($request->input('dieta')),
        ]);

        return response()->json([
            'message' => 'Dieta creada correctamente',
            'dieta' => $dieta
        ], 201);
    }

    public function show(Dieta $dieta)
    {
        $this->authorize('view', $dieta);

        return response()->json($dieta, 200);
    }

    public function update(Request $request, Dieta $dieta)
    {
        $this->authorize('update', $dieta);

        $request->validate([
            'semana' => 'sometimes|integer|min:1',
            'dieta' => 'nullable|array',
        ]);

        $dieta->update([
            'semana' => $request->input('semana', $dieta->semana),
            'dieta' => json_encode($request->input('dieta', json_decode($dieta->dieta, true))),
        ]);

        return response()->json([
            'message' => 'Dieta actualizada correctamente',
            'dieta' => $dieta
        ], 200);
    }

    public function destroy(Dieta $dieta)
    {
        $this->authorize('delete', $dieta);

        $dieta->delete();

        return response()->json(['message' => 'Dieta eliminada correctamente'], 200);
    }
}
