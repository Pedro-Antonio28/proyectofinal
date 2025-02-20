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
        $this->validateCurrentStep();

        // 🔥 Depuración: Si se ejecuta, verás este mensaje
        session()->flash('debug', 'Livewire ejecutó save() correctamente.');

        return redirect()->route('user.alimentos');
    }




    public function render()
    {
        return view('livewire.questionnaire')->layout('layouts.livewireLayout');
    }





}
