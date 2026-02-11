<?php

namespace App\Http\Controllers;

use App\Models\Ciclista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CiclistaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_ciclista = Auth::user()->id;

        $ciclistas = Ciclista::query()->where('id_user', '=', $id_ciclista)->get();

        // for ($i = 0; $i < count($ciclistas); $i++) {
        //     $ciclista = $ciclistas[$i];
        //     $user_ciclista = $ciclista->user;
            
        //     $ciclistas[$i] = [
        //         "id" => $ciclista["id"],
        //         "nombre" => $user_ciclista["name"],
        //         "apellido" => $ciclista["apellido"],
        //         "fecha_nacimiento" => $ciclista["fecha_nacimiento"],
        //         "peso_base" => $ciclista["peso_base"],
        //         "altura_base" => $ciclista["altura_base"],
        //     ];
        // }

        return $ciclistas;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // listar todos los cicistas de la bd en formato json
    public function listarCiclistasAPI(Ciclista $ciclista) {
        $ciclista = Ciclista::query()->orderBy('created_at', 'desc')->get();
        return response()->json($ciclista);
    }
}
