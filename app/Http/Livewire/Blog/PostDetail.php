<?php

namespace App\Http\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Alimento;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class PostDetail extends Component
{
    public Post $post;

    public $mostrarModal = false;
    public $diaSeleccionado = '';
    public $tipoComidaSeleccionado = '';
    public $postIdParaAñadir;

    public $cantidadSeleccionada = 100;


    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function lanzarModalAñadir($postId)
    {
        $this->postIdParaAñadir = $postId;
        $this->mostrarModal = true;
    }


    public function añadirPostADieta($postId)
    {

        $this->validate();

        $user = Auth::user();
        $post = \App\Models\Post::findOrFail($postId);

        // Obtener o crear la dieta actual del usuario
        $dieta = Dieta::deSemanaActual($user->id)->first();
        if (!$dieta) {
            $dietaService = new \App\Services\DietaService();
            $dieta = $dietaService->generarDietaSemanal($user);
        }

        // Día y tipo de comida por defecto (puedes permitir elegirlo en el futuro)
        $dia = now()->locale('es')->isoFormat('dddd'); // ej: 'lunes'
        $dia = ucfirst($dia); // Asegura capitalización: 'Lunes'
        $tipoComida = 'Extra'; // Sección extra para recetas manuales

        foreach ($post->ingredients as $ingredient) {
            // Buscar si ya existe el alimento
            $alimento = Alimento::where('nombre', $ingredient['name'])->first();

            // Si no existe, puedes omitir o crear un alimento placeholder
            if (!$alimento) continue;

            DietaAlimento::create([
                'dieta_id' => $dieta->id,
                'alimento_id' => $alimento->id,
                'dia' => $dia,
                'tipo_comida' => $tipoComida,
                'cantidad' => $ingredient['quantity'] ?? 100,
                'consumido' => false
            ]);
        }

        $this->dispatch('alimentoAgregado');
        session()->flash('success', 'Receta añadida a tu dieta del día ' . $dia);
    }

    public function guardarPostEnDieta()
    {
        $user = auth()->user();
        $post = \App\Models\Post::findOrFail($this->postIdParaAñadir);
        $cantidad = $this->cantidadSeleccionada;

        // Crear alimento si no existe
        $alimento = \App\Models\Alimento::firstOrCreate(
            ['nombre' => $post->title],
            [
                'calorias' => $post->macros['calories'] ?? 0,
                'proteinas' => $post->macros['protein'] ?? 0,
                'carbohidratos' => $post->macros['carbs'] ?? 0,
                'grasas' => $post->macros['fat'] ?? 0,
            ]
        );

        // Obtener o crear la dieta de esta semana
        $dieta = \App\Models\Dieta::deSemanaActual($user->id)->first();
        if (!$dieta) {
            $dietaService = new \App\Services\DietaService();
            $dieta = $dietaService->generarDietaSemanal($user);
        }

        // Guardar en dieta_alimentos
        \App\Models\DietaAlimento::create([
            'dieta_id' => $dieta->id,
            'alimento_id' => $alimento->id,
            'dia' => ucfirst($this->diaSeleccionado),
            'tipo_comida' => ucfirst($this->tipoComidaSeleccionado),
            'cantidad' => $cantidad,
            'consumido' => false
        ]);

        // Actualizar JSON
        $dietaJson = json_decode($dieta->dieta, true);
        $dia = ucfirst($this->diaSeleccionado);
        $comida = ucfirst($this->tipoComidaSeleccionado);

        if (!isset($dietaJson[$dia])) {
            $dietaJson[$dia] = [];
        }
        if (!isset($dietaJson[$dia][$comida])) {
            $dietaJson[$dia][$comida] = [];
        }

        $dietaJson[$dia][$comida][] = [
            'alimento_id'   => $alimento->id,
            'nombre'        => $post->title,
            'cantidad'      => $cantidad,
            'calorias'      => round(($post->macros['calories'] ?? 0) * $cantidad / 100),
            'proteinas'     => round(($post->macros['protein'] ?? 0) * $cantidad / 100, 1),
            'carbohidratos' => round(($post->macros['carbs'] ?? 0) * $cantidad / 100, 1),
            'grasas'        => round(($post->macros['fat'] ?? 0) * $cantidad / 100, 1),
        ];

        $dieta->update([
            'dieta' => json_encode($dietaJson)
        ]);

        $this->mostrarModal = false;
        session()->flash('success', 'Receta añadida a tu dieta correctamente.');
        $this->dispatch('alimentoAgregado');
    }

    public function rules()
    {
        return [
            'diaSeleccionado' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'tipoComidaSeleccionado' => 'required|in:Desayuno,Comida,Merienda,Cena',
            'cantidadSeleccionada' => 'required|numeric|min:1'
        ];
    }



    public function render()
    {
        return view('livewire.blog.post-detail')->layout('layouts.livewireLayout');
    }
}
