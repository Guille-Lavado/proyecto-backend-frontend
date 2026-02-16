<?php

use App\Http\Controllers\BloqueController;
use App\Http\Controllers\CiclistaController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\EntrenamientoController;
use Illuminate\Http\Request;
// use Illuminate\Routing\Route;

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

// Route::get('/ciclista', [CiclistaController::class, 'listarCiclistasAPI'])->name('ciclista.listarCiclistas');
Route::get('/bloque', [BloqueController::class, 'index'])->name('bloque.index');
Route::post('/bloque/crear', [BloqueController::class, 'store'])->name('bloque.store');
Route::get('/bloque/{id}', [BloqueController::class, 'show'])->name('bloque.show');
Route::delete('/bloque/{id}/eliminar', [BloqueController::class, 'destroy'])->name('bloque.destroy');

Route::get('/plan', [PlanController::class, 'index'])->name('plan.index');
Route::post('/plan/crear', [PlanController::class, 'store'])->name('plan.store');
Route::put('/plan/{id}', [PlanController::class, 'update'])->name('plan.update');
Route::delete('/plan/{id}', [PlanController::class, 'destroy'])->name('plan.destroy');

Route::get('/sesion', [SesionController::class, 'index'])->name('sesion.index');
Route::post('/sesion/crear', [SesionController::class, 'store'])->name('sesion.store');
Route::get('/sesion/{id}', [SesionController::class, 'show'])->name('sesion.show');
Route::delete('/sesion/{id}', [SesionController::class, 'destroy'])->name('sesion.destroy');

Route::post("/resultado/crear", [EntrenamientoController::class, "store"]);
Route::get("/resultado/{id}", [EntrenamientoController::class, "show"]);
