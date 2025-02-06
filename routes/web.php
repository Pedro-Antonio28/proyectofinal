<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\UserAlimentoController;


Route::get('/questionnaire/{step}', [QuestionnaireController::class, 'show'])->name('questionnaire.show');
Route::post('/questionnaire/store', [QuestionnaireController::class, 'store'])->name('questionnaire.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');



Route::get('/', function () {
    return view('welcome');
})->name('home');

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




Route::post('/questionnaire', [UserController::class, 'saveQuestionnaire'])->name('questionnaire.post');

Route::resource('alimentos', AlimentoController::class)->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/seleccionar-alimentos', [UserAlimentoController::class, 'index'])->name('user.alimentos');
    Route::post('/seleccionar-alimentos', [UserAlimentoController::class, 'store'])->name('user.alimentos.store');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
