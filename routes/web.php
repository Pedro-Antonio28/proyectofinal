<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DietaController;
use App\Http\Controllers\NutricionistaController;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\AgregarAlimento;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\EditarAlimento;
use App\Http\Livewire\Questionnaire;
use App\Http\Livewire\UserAlimentos;
use App\Http\Livewire\WelcomePage;
use App\Middleware\RoleMiddleware;
use App\Middleware\SetLocale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Livewire\Blog\PostForm;
use App\Http\Livewire\Blog\PostList;
use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ExcelDietaController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\TelegramWebhookController;


Route::get('/change-language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es'])) {
        Session::put('locale', $locale);
        Session::save();
        App::setLocale($locale);
        Config::set('app.locale', $locale);
    }
    return redirect()->back()->withCookie(cookie()->forever('locale', $locale));
})->name('change.language');




Route::middleware(SetLocale::class)->group(function () {
    Route::get('/', WelcomePage::class)->name('home');

    // Rutas de autenticación
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/questionnaire', Questionnaire::class)->middleware('auth')->name('questionnaire.show');

    Route::middleware(['auth'])->group(function () {
        Route::get('/seleccionar-alimentos', UserAlimentos::class)->name('user.alimentos');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
    });
    Route::get('/agregar-alimento/{dia}/{tipoComida}', AgregarAlimento::class)
        ->name('agregar.alimento');
    Route::get('/editar-alimento/{dia}/{tipoComida}/{alimentoId}', EditarAlimento::class)
        ->name('editar.alimento');
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    Route::middleware(RoleMiddleware::class . ':nutricionista')->group(function () {
        Route::get('/nutricionista', [NutricionistaController::class, 'index'])->name('nutricionista.panel');
        Route::get('/nutricionista/clientes/{id}', [NutricionistaController::class, 'verDieta'])->name('nutricionista.cliente.dieta');
        Route::get('/nutricionista/dieta/{id}/agregar', [NutricionistaController::class, 'mostrarFormularioAgregar'])->name('nutricionista.dieta.form_agregar');
        Route::post('/nutricionista/dieta/{id}/add', [NutricionistaController::class, 'agregarAlimento'])->name('nutricionista.dieta.add');
        Route::get('/nutricionista/dieta/{clienteId}/{dia}/{tipoComida}/{alimentoId}/editar', [NutricionistaController::class, 'mostrarFormularioEdicion'])->name('nutricionista.dieta.editar');
        Route::put('/nutricionista/dieta/{clienteId}/{dia}/{tipoComida}/{alimentoId}/update', [NutricionistaController::class, 'editarAlimento'])->name('nutricionista.dieta.update');
        Route::delete('/nutricionista/dieta/{clienteId}/{dia}/{tipoComida}/{alimentoId}/delete', [NutricionistaController::class, 'eliminarAlimento'])->name('nutricionista.dieta.delete');
    });

    Route::get('/admin', function () {
        return "Bienvenido, Administrador";
    })->middleware(RoleMiddleware::class . ':admin');

    Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
        Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
        Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.delete');
        Route::get('/admin/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
        Route::get('/admin/users/{id}/dieta', [AdminController::class, 'verDieta'])->name('admin.users.dieta');
        Route::delete('/admin/dieta/{id}/delete', [AdminController::class, 'eliminarDieta'])->name('admin.dieta.delete');
        Route::get('/admin/dieta/{id}/editar-alimento', [AdminController::class, 'editarAlimento'])->name('admin.dieta.editar-alimento');
        Route::post('/admin/dieta/{id}/update-alimento', [AdminController::class, 'actualizarAlimento'])->name('admin.dieta.update-alimento');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/posts', PostList::class)->name('posts.index');
        Route::get('/posts/create', PostForm::class)->name('posts.create');
        Route::get('/posts/{post}/edit', PostForm::class)->name('posts.edit');
        Route::get('/posts/{post}', \App\Http\Livewire\Blog\PostDetail::class)->name('post.show');

    });
    // ... otras rutas que usen la configuración del idioma
});


Route::post('/admin/users/{id}/update-email', [AdminController::class, 'updateEmail'])->name('admin.users.update.email');
Route::post('/admin/users/{id}/update-password', [AdminController::class, 'updatePassword'])->name('admin.users.update.password');

Route::resource('alimentos', AlimentoController::class)->middleware('auth');



Route::get('/dieta/pdf/{dia}', [DietaController::class, 'pdf'])
    ->name('pdf.dieta')
    ->middleware('auth');




Route::get('/blog/export/excel', [ExportController::class, 'exportExcel'])->name('blog.export.excel');

Route::get('/dieta/exportar/excel', [ExcelDietaController::class, 'export'])->name('dieta.exportar.excel');

Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

Route::get('/paypal/pay', [PayPalController::class, 'createTransaction'])->name('paypal.create');
Route::get('/paypal/success', [PayPalController::class, 'successTransaction'])->name('paypal.success');
Route::get('/paypal/cancel', [PayPalController::class, 'cancelTransaction'])->name('paypal.cancel');

