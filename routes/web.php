<?php

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CiclistaController;

Route::get('/', function () {
  $visited = DB::select('select * from places where visited = ?', [1]); 
  $togo = DB::select('select * from places where visited = ?', [0]);

  return view('travel_list', ['visited' => $visited, 'togo' => $togo ] );
});

Route::get('/ciclistas', [CiclistaController::class, 'index'])->name('ciclistas.index');
