<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Jobs\SendVerificationEmail;
class AuthController extends Controller
{
    // Mostrar la vista de inicio de sesión
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard'); // Evita que un usuario autenticado vea el login
        }
        return view('auth.login');
    }


    // Procesar el inicio de sesión
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Credenciales incorrectas.']);
        }

        // 🔒 Verificar si ha completado el cuestionario
        if (
            !$user->peso ||
            !$user->altura ||
            !$user->age ||
            !$user->gender ||
            !$user->objetivo ||
            !$user->actividad
        ) {
            return redirect()->route('questionnaire.show');
        }

        // 🔒 Verificar si ha seleccionado alimentos
        if ($user->alimentos()->count() === 0) {
            return redirect()->route('user.alimentos');
        }

        // ✅ Todo correcto, puede entrar
        return redirect()->route('dashboard');
    }




    // Cerrar sesión
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }


    public function showRegister()
    {
        return view('auth.register');

    }



    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Puedes agregar campos extra si quieres
        ]);

        // Iniciar sesión automáticamente
        Auth::login($user);

        // Redirigir al cuestionario
        return redirect()->route('questionnaire.show');
    }





}

