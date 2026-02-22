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

        $res_planes = [];
        foreach($planes as $plan) {
            $res_planes[] = [
                "id" => $plan["id"],
                "nombre" => $plan["nombre"],
                "descripcion" => $plan["descripcion"],
                "fecha_inicio" => $plan["fecha_inicio"],
                "fecha_fin" => $plan["fecha_fin"],
                "objetivo" => $plan["objetivoid"],
                "activo" => $plan["activo"]
            ];
        }

        return response()->json($res_planes, 200);
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

    public function getSesionPlan() {
        $sesionPlanes = PlanEntrenamiento::with(["sesiones"])->get();

        $res_sesionPlanes = [];
        foreach($sesionPlanes as $sesionPlan) {
            $sesiones = [];
            foreach($sesionPlan["sesiones"] as $sesion) {
                $sesiones[] = [
                    "id" => $sesion["id"],
                    "fecha" => $sesion["fecha"],
                    "nombre" => $sesion["nombreid"],
                    "descripcion" => $sesion["descripcion"],
                    "completada" => $sesion["completada"],
                ];
            }

            $res_sesionPlanes[] = [
                "id" => $sesionPlan["id"],
                "nombre" => $sesionPlan["nombre"],
                "descripcion" => $sesionPlan["descripcion"],
                "fecha_inicio" => $sesionPlan["fecha_inicio"],
                "fecha_fin" => $sesionPlan["fecha_fin"],
                "objetivo" => $sesionPlan["objetivoid"],
                "activo" => $sesionPlan["activo"],
                "sesiones" => $sesiones
            ];
        }

        return response()->json($res_sesionPlanes, 200);
    }

    public function crearSesionPlan(Request $request) {
        $validated = $request->validate([
            'id_ciclista'            => 'required|exists:ciclistas,id',
            'nombre'                 => 'required|string|max:255',
            'descripcion'            => 'nullable|string',
            'fecha_inicio'           => 'required|date',
            'fecha_fin'              => 'required|date|after_or_equal:fecha_inicio',
            'objetivo'               => 'nullable|string',
            'activo'                 => 'boolean',
            'sesiones'               => 'required|array',
            'sesiones.*.fecha'       => 'required|date',
            'sesiones.*.nombre'      => 'required|string|max:255',
            'sesiones.*.descripcion' => 'nullable|string',
            'sesiones.*.completada'  => 'boolean'
        ]);

        $plan = PlanEntrenamiento::create([
            'id_ciclista' => $request->id_ciclista,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'objetivo' => $request->objetivo,
            'activo' => $request->activo
        ]);

        foreach ($request->sesiones as $sesionData) {
                $plan->sesiones()->create([
                    'id_plan' => $plan->id,
                    'nombre' => $sesionData['nombre'],
                    'fecha' => $sesionData['fecha'],
                    'descripcion' => $sesionData['descripcion'],
                    'completada' => $sesionData['completada'],
                ]);
        }

        return response()->json([
            'message' => 'Plan con sesiones creado',
            'data' => $plan->load('sesiones')
        ], 201);
    }

    public function deleteSesionPlan($id) {
        // 1. Buscamos el plan con sus sesiones
        $plan = PlanEntrenamiento::with('sesiones')->findOrFail($id);

        foreach ($plan->sesiones as $sesion) {
            // 2. Desvinculasmos los bloques en la tabla pivote 
            $sesion->bloques()->detach();
            
            // 3. Borramos la sesión
            $sesion->delete();
        }

        // 4. Borramos el plan
        $plan->delete();

        return response()->json([
            'message' => 'Plan y sesiones eliminados correctamente'
        ], 200);
    }
}
