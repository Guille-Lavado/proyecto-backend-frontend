<?php

namespace App\Http\Controllers;

use App\Models\PlanEntrenamiento;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Listar todos los planes en formato json
    public function getAll()
    {
        $planes = PlanEntrenamiento::all();
        return response()->json($planes, 200);
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
            'id_ciclista'  => 'required|exists:ciclistas,id',
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'objetivo'     => 'nullable|string',
            'activo'       => 'boolean'
        ]);

        $plan = PlanEntrenamiento::create($validated);

        return response()->json([
            'message' => 'Plan creado',
            'data' => $plan
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
        $plan = PlanEntrenamiento::findOrFail($id);

        $validated = $request->validate([
            'nombre'       => 'string|max:255',
            'descripcion'  => 'nullable|string',
            'fecha_inicio' => 'date',
            'fecha_fin'    => 'date|after_or_equal:fecha_inicio',
            'objetivo'     => 'nullable|string',
            'activo'       => 'boolean'
        ]);

        $plan->update($validated);

        return response()->json([
            'message' => 'Plan actualizado',
            'data' => $plan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plan = PlanEntrenamiento::findOrFail($id);
        $plan->delete();

        return response()->json([
            'message' => 'Plan eliminado'
        ], 200);
    }
}
