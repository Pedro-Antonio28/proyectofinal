<?php

use App\Http\Livewire\WelcomePage;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\UserAlimentoController;
use App\Http\Livewire\Questionnaire;
use App\Http\Livewire\UserAlimentos;
use App\Http\Livewire\EditarAlimento;
use App\Http\Livewire\AgregarAlimento;
use App\Http\Controllers\DietaController;
use App\Middleware\RoleMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NutricionistaController;


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



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', App\Http\Livewire\Dashboard::class)->name('dashboard');
});



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

// Ruta para agregar alimento
Route::get('/agregar-alimento/{dia}/{tipoComida}', App\Http\Livewire\AgregarAlimento::class)
    ->name('agregar.alimento');

Route::get('/editar-alimento/{dia}/{tipoComida}/{alimentoId}', App\Http\Livewire\EditarAlimento::class)
    ->name('editar.alimento');


Route::get('/dieta/pdf/{dia}', [DietaController::class, 'pdf'])
    ->name('pdf.dieta')
    ->middleware('auth');

Route::get('/admin', function () {
    return "Bienvenido, Administrador";
})->middleware(RoleMiddleware::class . ':admin');

Route::middleware([RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.delete');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/{id}/update', [AdminController::class, 'update'])->name('admin.users.update');
});

Route::get('/admin/users/{id}/dieta', [AdminController::class, 'verDieta'])->name('admin.users.dieta');

Route::delete('/admin/dieta/{id}/delete', [AdminController::class, 'eliminarDieta'])->name('admin.dieta.delete');

Route::get('/admin/dieta/{id}/editar-alimento', [AdminController::class, 'editarAlimento'])->name('admin.dieta.editar-alimento');

Route::post('/admin/dieta/{id}/update-alimento', [AdminController::class, 'actualizarAlimento'])->name('admin.dieta.update-alimento');


Route::middleware(\App\Middleware\RoleMiddleware::class . ':nutricionista')->group(function () {
    Route::get('/nutricionista', [NutricionistaController::class, 'index'])->name('nutricionista.panel');
    Route::get('/nutricionista/clientes/{id}', [NutricionistaController::class, 'verDieta'])->name('nutricionista.cliente.dieta');
    Route::post('/nutricionista/dieta/{id}/add', [NutricionistaController::class, 'agregarAlimento'])->name('nutricionista.dieta.add');
    Route::post('/nutricionista/dieta/{id}/update', [NutricionistaController::class, 'editarAlimento'])->name('nutricionista.dieta.update');
    Route::delete('/nutricionista/dieta/{id}/delete', [NutricionistaController::class, 'eliminarAlimento'])->name('nutricionista.dieta.delete');
});
require __DIR__.'/auth.php';
