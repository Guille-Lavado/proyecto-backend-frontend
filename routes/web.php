<?php

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CiclistaController;
use App\Http\Controllers\BloqueController;

// Route::get('/', function () {
//   $visited = DB::select('select * from places where visited = ?', [1]); 
//   $togo = DB::select('select * from places where visited = ?', [0]);

//   return view('travel_list', ['visited' => $visited, 'togo' => $togo ] );
// });

// Route::get('/ciclista', [CiclistaController::class, 'index'])->name('ciclista.index');
Route::get('/bloque', [BloqueController::class, 'index'])->name('bloque.index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');

// Route::get("/", [CiclistaController::class, 'index'])->name('ciclista.index');
