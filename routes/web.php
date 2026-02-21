<?php

use Illuminate\Support\Facades\Route;

// Captura la raíz y devuelve nuestra vista SPA principal.
Route::get('/', 'CiclistaController@index')->name('app');


// Para evitar errores 404 si el usuario recarga la página en una vista distinta a la raíz.
Route::fallback(function () {
    return view('app');
});