<?php

use App\Http\Livewire\WelcomePage;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\UserAlimentoController;
use App\Http\Livewire\Questionnaire;
use App\Http\Livewire\UserAlimentos;






Route::get('/questionnaire', Questionnaire::class)->name('questionnaire.show');








Route::get('/', WelcomePage::class)->name('home');




Route::get('/sobre-nosotros', function () {
    return view('about');
})->name('about');

Route::get('/blog', function () {
    return view('blog');
})->name('blog');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Ruta protegida para el dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');




// Ruta para mostrar la vista de registro
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Ruta para procesar el registro
Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('register.post');





Route::resource('alimentos', AlimentoController::class)->middleware('auth');

Route::middleware(['auth'])->group(function () {


    Route::get('/seleccionar-alimentos', UserAlimentos::class)->name('user.alimentos');
});





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
