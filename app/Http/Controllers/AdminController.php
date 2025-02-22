<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\DietaAlimento;
use App\Models\Dieta;

class AdminController extends Controller
{
    // Listar todos los usuarios en la vista de administración
    public function index()
    {
        $usuarios = User::with('dieta')->get(); // Carga los usuarios con su dieta
        return view('admin.users', compact('usuarios'));
    }


    // Mostrar formulario de edición
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.edit-user', compact('usuario'));
    }

    // Actualizar datos del usuario
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $usuario->email = $request->input('email');
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->input('password'));
        }
        $usuario->save();

        return redirect()->route('admin.users')->with('success', 'Usuario actualizado correctamente');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.users')->with('success', 'Usuario eliminado correctamente');
    }

    public function verDieta($id)
    {
        $usuario = User::with('dieta.alimentos.alimento')->findOrFail($id);

        if (!$usuario->dieta) {
            return redirect()->route('admin.users')->with('error', 'Este usuario no tiene una dieta asignada.');
        }

        return view('admin.dieta', compact('usuario'));
    }

    public function eliminarDieta($id)
    {
        $dieta = Dieta::findOrFail($id);
        $dieta->delete();

        return redirect()->route('admin.users')->with('success', 'Dieta eliminada correctamente.');
    }


    public function editarAlimento($id)
    {
        $alimento = DietaAlimento::findOrFail($id);
        return view('admin.editar-alimento', compact('alimento'));
    }


    public function actualizarAlimento(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|numeric|min:1',
        ]);

        $alimento = DietaAlimento::findOrFail($id);
        $alimento->update(['cantidad' => $request->input('cantidad')]);

        // Actualizar el JSON de la dieta
        $this->actualizarDietaJSON($alimento->dieta);

        return redirect()->route('admin.users.dieta', ['id' => $alimento->dieta->user_id])
            ->with('success', 'Alimento actualizado correctamente.');
    }


    private function actualizarDietaJSON($dieta)
    {
        // Obtener todos los alimentos de la dieta
        $dietaAlimentos = $dieta->alimentos()->with('alimento')->get();

        // Inicializar la estructura JSON
        $dietaJson = [];

        // Organizar los alimentos por día y tipo de comida
        foreach ($dietaAlimentos as $alimento) {
            $dia = $alimento->dia;
            $tipoComida = $alimento->tipo_comida;

            $dietaJson[$dia][$tipoComida][] = [
                'alimento_id'   => $alimento->id,
                'nombre'        => $alimento->alimento->nombre,
                'cantidad'      => $alimento->cantidad,
                'calorias'      => round(($alimento->alimento->calorias * $alimento->cantidad) / 100),
                'proteinas'     => round(($alimento->alimento->proteinas * $alimento->cantidad) / 100, 1),
                'carbohidratos' => round(($alimento->alimento->carbohidratos * $alimento->cantidad) / 100, 1),
                'grasas'        => round(($alimento->alimento->grasas * $alimento->cantidad) / 100, 1),
            ];
        }

        // Guardar la nueva versión del JSON en la tabla dietas
        $dieta->update(['dieta' => json_encode($dietaJson)]);
    }


}
