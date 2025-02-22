<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF; // Asegúrate de importar la fachada: use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Dieta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DietaController extends Controller
{
    public function pdf($dia)
    {
        $user = Auth::user();
        $semanaActual = Carbon::now()->weekOfYear;

        $dieta = Dieta::where('user_id', $user->id)
            ->where('semana', $semanaActual)
            ->with('alimentos.alimento')
            ->first();

        if (!$dieta) {
            return redirect()->back()->with('error', 'No se encontró dieta para la semana actual.');
        }

        $dietaJson = json_decode($dieta->dieta, true);
        $comidas = $dietaJson[$dia] ?? [];

        // Prepara los datos a pasar a la vista
        $data = [
            'dia'     => $dia,
            'comidas' => $comidas,
            'user'    => $user
        ];

        // Cargar la vista y generar el PDF
        $pdf = PDF::loadView('dieta.pdf', $data);

        return $pdf->download("dieta_{$dia}.pdf");
    }
}
