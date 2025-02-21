<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Questionnaire extends Component
{
    public $step = 1;
    public $gender, $age, $peso, $altura, $objetivo, $actividad;

    public function mount()
    {

        // Cargar datos desde la sesión si existen
        $this->gender = session('gender', '');
        $this->age = session('age', '');
        $this->peso = session('peso', '');
        $this->altura = session('altura', '');
        $this->objetivo = session('objetivo', '');
        $this->actividad = session('actividad', '');

    }

    public function nextStep()
    {
        $this->validateCurrentStep(); // 🔥 Validar antes de avanzar
        if ($this->step < 6) {
            $this->step++;
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function validateCurrentStep()
    {
        $rules = [
            1 => ['gender' => 'required|in:male,female'],
            2 => ['age' => 'required|integer|min:10|max:100'],
            3 => ['peso' => 'required|numeric|min:30|max:300'],
            4 => ['altura' => 'required|numeric|min:100|max:250'],
            5 => ['objetivo' => 'required|string'],
            6 => ['actividad' => 'required|string'],
        ];

        if (isset($rules[$this->step])) {
            $this->validate($rules[$this->step]);
        }

    }


    public function save()
    {
        $user = auth()->user();

        // Verifica si el usuario está autenticado
        if (!$user) {
            session()->flash('error', 'Debes iniciar sesión para completar el cuestionario.');
            return;
        }

        // Cálculo del TMB (Tasa Metabólica Basal)
        if ($this->gender == 'male') {
            $tmb = 10 * $this->peso + 6.25 * $this->altura - 5 * 25 + 5; // Suponiendo edad promedio de 25
        } else {
            $tmb = 10 * $this->peso + 6.25 * $this->altura - 5 * 25 - 161;
        }

        // Multiplicador según nivel de actividad
        $multiplicadores = [
            'sedentario' => 1.2,
            'ligero' => 1.375,
            'moderado' => 1.55,
            'intenso' => 1.725
        ];

        $factor = $multiplicadores[$this->actividad] ?? 1.2; // Por defecto sedentario
        $calorias = $tmb * $factor;

        // Ajuste según objetivo
        if ($this->objetivo == 'perder_peso') {
            $calorias -= 500;
        } elseif ($this->objetivo == 'ganar_musculo') {
            $calorias += 500;
        }

        // Cálculo de macronutrientes (ejemplo: 40% carbos, 30% proteínas, 30% grasas)
        $proteinas = ($calorias * 0.3) / 4;  // 1g de proteína = 4 calorías
        $carbohidratos = ($calorias * 0.4) / 4; // 1g de carbohidrato = 4 calorías
        $grasas = ($calorias * 0.3) / 9; // 1g de grasa = 9 calorías

        // Guardamos los datos en la base de datos
        $user->update([
            'gender' => $this->gender,
            'age' => $this->age,
            'peso' => $this->peso,
            'altura' => $this->altura,
            'objetivo' => $this->objetivo,
            'actividad' => $this->actividad,
            'calorias_necesarias' => round($calorias),
            'proteinas' => round($proteinas),
            'carbohidratos' => round($carbohidratos),
            'grasas' => round($grasas),
        ]);

        session()->flash('message', 'Cuestionario completado correctamente y datos guardados.');

        return redirect()->route('user.alimentos');
    }







    public function render()
    {
        return view('livewire.questionnaire')->layout('layouts.livewireLayout');
    }





}
