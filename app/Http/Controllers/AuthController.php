<?php

namespace App\Http\Controllers;

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

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Bloquear acceso si el email no está verificado
            if (!$user->email_verified_at) {
                Auth::logout();
                return back()->withErrors(['email' => 'Debes verificar tu correo antes de acceder.']);
            }

            if (!$user->peso || !$user->altura || !$user->objetivo || !$user->actividad) {
                return redirect()->route('questionnaire.show');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.']);
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



    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null,
        ]);

        // Enviar el email en segundo plano
        SendVerificationEmail::dispatch($user);

        \Log::info('Ejecutando AuthController::register para: ' . $request->email);

        return redirect()->route('login')->with('success', 'Te hemos enviado un correo de verificación. Verifica tu email antes de iniciar sesión.');
    }




}

