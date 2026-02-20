<?php

namespace App\Http\Controllers;

use App\Models\BloqueEntrenamiento;
use Illuminate\Http\Request;

class BloqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // listar todos los Bloques de un usuario de la bd en formato json
    public function getAll()
    {
        $bloques = BloqueEntrenamiento::query()->orderBy('created_at', 'desc')->get();

        return response()->json($bloques, 200);
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
        // 1. Validar los datos
        $validatedData = $request->validate([
            'nombre'            => 'required|string|max:100',
            'descripcion'       => 'nullable|string',
            'tipo'              => 'required|string|max:50',
            'duracion_estimada' => 'required|integer',
            'potencia_pct_min'  => 'nullable|integer',
            'potencia_pct_max'  => 'nullable|integer',
            'pulso_pct_max'     => 'nullable|integer',
            'pulso_reserva_pct' => 'nullable|integer',
            'comentario'        => 'nullable|string',
        ]);

        // 2. Insertar en la base de datos
        $bloque = BloqueEntrenamiento::create($validatedData);

        // 3. Retornar respuesta
        return response()->json([
            'message' => 'Bloque creado',
            'data' => $bloque
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bloque = BloqueEntrenamiento::findOrFail($id);

        return response()->json($bloque, 200);
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
        $bloque = BloqueEntrenamiento::findOrFail($id);
        $bloque->delete();

        return response()->json([
            'message' => 'Bloque eliminado'
        ], 200);
    }
}
