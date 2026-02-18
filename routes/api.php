<?php

use App\Http\Controllers\BloqueController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\EntrenamientoController;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/bloque', [BloqueController::class, 'index']);
    Route::post('/bloque/crear', [BloqueController::class, 'store']);
    Route::get('/bloque/{id}', [BloqueController::class, 'show']);
    Route::delete('/bloque/{id}/eliminar', [BloqueController::class, 'destroy']);

    Route::get('/plan', [PlanController::class, 'index']);
    Route::post('/plan/crear', [PlanController::class, 'store']);
    Route::put('/plan/{id}', [PlanController::class, 'update']);
    Route::delete('/plan/{id}', [PlanController::class, 'destroy']);

    Route::get('/sesion', [SesionController::class, 'index']);
    Route::post('/sesion/crear', [SesionController::class, 'store']);
    Route::get('/sesion/{id}', [SesionController::class, 'show']);
    Route::delete('/sesion/{id}', [SesionController::class, 'destroy']);

    Route::post("/resultado/crear", [EntrenamientoController::class, "store"]);
    Route::get("/resultado/{id}", [EntrenamientoController::class, "show"]);
});


