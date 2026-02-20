<?php

namespace App\Http\Controllers;

use App\Models\SesionEntrenamiento;
use Illuminate\Http\Request;

class SesionController extends Controller
{
    /**
     * Devuelve una lista con todas las sesiones.
     * Si se usan los parametros offset y limit se devuelve
     * el número de sesiones entre offset y limit.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $offset = $_GET["offset"] ?? "";
        $limit = $_GET["limit"] ?? "";

        if($offset && $limit) {
            $sesiones = SesionEntrenamiento::offset($offset)->limit($limit)->get();
        } else {
            $sesiones = SesionEntrenamiento::all();
        }
        
        return response()->json($sesiones, 200);
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
        $validated = $request->validate([
            'id_plan'     => 'required|exists:plan_entrenamientos,id',
            'fecha'       => 'required|date',
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'completada'  => 'boolean'
        ]);

        $sesion = SesionEntrenamiento::create($validated);

        return response()->json([
            'message' => 'Sesión creada',
            'data' => $sesion
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
        // with(['bloque'])
        $sesion = SesionEntrenamiento::findOrFail($id);
        $bloques = $sesion->bloques;
        return response()->json($bloques, 200);
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
        $sesion = SesionEntrenamiento::findOrFail($id);
        $sesion->delete();

        return response()->json([
            'message' => 'Sesión eliminada'
        ], 200);
    }
}
