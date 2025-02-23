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

        // Buscar el usuario sin aplicar el Scope Global
        $user = User::where('email', $request->email)->first();


        // Si no existe el usuario, o la contraseña es incorrecta, devolver error
        if (!$user || !Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Credenciales incorrectas.']);
        }

        // Bloquear acceso si el email no está verificado
        if (!$user->email_verified_at) {
            Auth::logout();
            return back()->withErrors(['email' => 'Debes verificar tu correo antes de acceder.']);
        }

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
            'email_verified_at' => null,
        ]);

        // Enviar el email en segundo plano
        SendVerificationEmail::dispatch($user);

        \Log::info('Ejecutando AuthController::register para: ' . $request->email);

        return redirect()->route('login')->with('success', 'Te hemos enviado un correo de verificación. Verifica tu email antes de iniciar sesión.');
    }




}

