<?php


use \App\Http\Controllers\Api\V1\ApiAlimentoController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\V1\ApiPostController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/alimentos', [ApiAlimentoController::class, 'index']);
Route::get('/alimentos/{id}', [ApiAlimentoController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/alimentos', [ApiAlimentoController::class, 'store']);
    Route::put('/alimentos/{id}', [ApiAlimentoController::class, 'update']);
    Route::delete('/alimentos/{id}', [ApiAlimentoController::class, 'destroy']);
});

Route::get('/test-api', function (Request $request) {
    return response()->json(['message' => 'Laravel está recibiendo la petición correctamente']);
});


Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json(['token' => $token], 200);
});



Route::get('/posts', [ApiPostController::class, 'index']);
Route::get('/posts/{post}', [ApiPostController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [ApiPostController::class, 'store']);
    Route::put('/posts/{post}', [ApiPostController::class, 'update']);
    Route::delete('/posts/{post}', [ApiPostController::class, 'destroy']);
});
