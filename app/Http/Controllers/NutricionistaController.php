<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NutricionistaController extends Controller
{

    use AuthorizesRequests;
    public function index()
    {
        $nutricionista = auth()->user();
        $clientes = $nutricionista->clientes;
        return view('nutricionista.nutricionista_panel', compact('clientes'));

    }

    public function verDieta($id)
    {
        $cliente = User::findOrFail($id);
        $dieta = Dieta::where('user_id', $id)->firstOrFail();

        // Verifica si el usuario puede ver la dieta
        $this->authorize('view', $dieta);

        return view('nutricionista.dieta', compact('cliente', 'dieta'));
    }



    public function agregarAlimento(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'calorias' => 'required|numeric|min:0',
            'proteinas' => 'required|numeric|min:0',
            'carbohidratos' => 'required|numeric|min:0',
            'grasas' => 'required|numeric|min:0',
            'cantidad' => 'required|numeric|min:1',
            'dia' => 'required|string',
            'tipo_comida' => 'required|string',
        ]);

        // Buscar si el alimento ya existe en la base de datos
        $alimento = \App\Models\Alimento::firstOrCreate([
            'nombre' => $request->input('nombre'),
        ], [
            'calorias' => $request->input('calorias'),
            'proteinas' => $request->input('proteinas'),
            'carbohidratos' => $request->input('carbohidratos'),
            'grasas' => $request->input('grasas'),
        ]);

        // Obtener la dieta del cliente
        $dieta = \App\Models\Dieta::where('user_id', $id)->firstOrFail();

        // Decodificar el JSON de la dieta actual
        $dietaData = json_decode($dieta->dieta, true) ?? [];

        // Agregar el nuevo alimento al día y tipo de comida correspondientes
        $dietaData[$request->input('dia')][$request->input('tipo_comida')][] = [
            'alimento_id'   => $alimento->id,
            'nombre'        => $alimento->nombre,
            'cantidad'      => $request->input('cantidad'),
            'calorias'      => round(($alimento->calorias * $request->input('cantidad')) / 100, 1),
            'proteinas'     => round(($alimento->proteinas * $request->input('cantidad')) / 100, 1),
            'carbohidratos' => round(($alimento->carbohidratos * $request->input('cantidad')) / 100, 1),
            'grasas'        => round(($alimento->grasas * $request->input('cantidad')) / 100, 1),
        ];

        // Guardar la dieta actualizada en la base de datos
        $dieta->update(['dieta' => json_encode($dietaData)]);

        return redirect()->route('nutricionista.cliente.dieta', $id)
            ->with('success', 'Alimento agregado correctamente a la dieta del cliente.');
    }




    public function editarAlimento(Request $request, $clienteId, $dia, $tipoComida, $alimentoId)
    {
        $dieta = Dieta::where('user_id', $clienteId)->firstOrFail();

        // Verifica si el usuario puede editar la dieta
        $this->authorize('update', $dieta);

        $request->validate([
            'cantidad' => 'required|numeric|min:1',
        ]);

        // Obtener la dieta del cliente
        $dieta = \App\Models\Dieta::where('user_id', $clienteId)->firstOrFail();
        $dietaData = json_decode($dieta->dieta, true);

        // Buscar el alimento en la dieta y actualizar la cantidad
        foreach ($dietaData[$dia][$tipoComida] as $index => $alimento) {
            if ($alimento['alimento_id'] == $alimentoId) {
                $dietaData[$dia][$tipoComida][$index]['cantidad'] = $request->input('cantidad');
                $dietaData[$dia][$tipoComida][$index]['calorias'] = round(($alimento['calorias'] * $request->input('cantidad')) / 100, 1);
                $dietaData[$dia][$tipoComida][$index]['proteinas'] = round(($alimento['proteinas'] * $request->input('cantidad')) / 100, 1);
                $dietaData[$dia][$tipoComida][$index]['carbohidratos'] = round(($alimento['carbohidratos'] * $request->input('cantidad')) / 100, 1);
                $dietaData[$dia][$tipoComida][$index]['grasas'] = round(($alimento['grasas'] * $request->input('cantidad')) / 100, 1);
                break;
            }
        }

        // Guardar la dieta actualizada en la base de datos
        $dieta->update(['dieta' => json_encode($dietaData)]);

        return redirect()->route('nutricionista.cliente.dieta', $clienteId)
            ->with('success', 'Alimento actualizado correctamente.');
    }



    public function eliminarAlimento($clienteId, $dia, $tipoComida, $alimentoId)
    {
        $dieta = Dieta::where('user_id', $clienteId)->firstOrFail();

        // Verifica si el usuario puede eliminar alimentos de la dieta
        $this->authorize('delete', $dieta);



        $dieta = \App\Models\Dieta::where('user_id', $clienteId)->firstOrFail();



        $dietaData = json_decode($dieta->dieta, true);

        // Filtrar el alimento a eliminar
        $dietaData[$dia][$tipoComida] = array_filter($dietaData[$dia][$tipoComida], function ($alimento) use ($alimentoId) {
            return $alimento['alimento_id'] != $alimentoId;
        });

        // Limpiar comidas vacías
        if (empty($dietaData[$dia][$tipoComida])) {
            unset($dietaData[$dia][$tipoComida]);
        }

        if (empty($dietaData[$dia])) {
            unset($dietaData[$dia]);
        }

        // Guardar la dieta actualizada
        $dieta->update(['dieta' => json_encode($dietaData)]);

        return redirect()->route('nutricionista.cliente.dieta', $clienteId)
            ->with('success', 'Alimento eliminado correctamente de la dieta.');
    }



    public function mostrarFormularioEdicion($clienteId, $dia, $tipoComida, $alimentoId)
    {
        // Obtener la dieta del cliente
        $dieta = \App\Models\Dieta::where('user_id', $clienteId)->firstOrFail();
        $dietaData = json_decode($dieta->dieta, true);

        // Buscar el alimento en la dieta
        $alimentoSeleccionado = null;
        foreach ($dietaData[$dia][$tipoComida] as $index => $alimento) {
            if ($alimento['alimento_id'] == $alimentoId) {
                $alimentoSeleccionado = $alimento;
                $alimentoSeleccionado['index'] = $index; // Guardamos el índice para modificarlo después
                break;
            }
        }

        if (!$alimentoSeleccionado) {
            return redirect()->back()->with('error', 'El alimento no se encontró en la dieta.');
        }

        return view('nutricionista.editar_alimento', compact('clienteId', 'dia', 'tipoComida', 'alimentoSeleccionado'));
    }


    public function mostrarFormularioAgregar($id)
    {
        $cliente = User::findOrFail($id);
        return view('nutricionista.agregar_alimento', compact('cliente'));
    }


}

