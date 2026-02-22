<?php

namespace App\Http\Controllers;

use App\Models\Entrenamiento;
use Illuminate\Http\Request;

class EntrenamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $entrenamientos = Entrenamiento::all();
        
        $res_entrenamientos = [];
        foreach($entrenamientos as $entrenamiento) {
            $res_entrenamientos[] = [
                "id" => $entrenamiento["id"],
                "id_ciclista" => $entrenamiento["id_ciclista"],
                "id_bicicleta" => $entrenamiento["id_bicicleta"],
                "id_sesion" => $entrenamiento["id_sesion"],
                "fecha" => $entrenamiento["fecha"],
                "duracion" => $entrenamiento["duracion"],
                "kilometros" => $entrenamiento["kilometros"],
                "recorrido" => $entrenamiento["recorrido"],
                "pulso_medio" => $entrenamiento["pulso_medio"],
                "pulso_max" => $entrenamiento["pulso_max"],
                "potencia_media" => $entrenamiento["potencia_media"],
                "potencia_normalizada" => $entrenamiento["potencia_normalizada"],
                "velocidad_media" => $entrenamiento["velocidad_media"],
                "puntos_estres_tss" => $entrenamiento["puntos_estres_tss"],
                "factor_intensidad_if" => $entrenamiento["factor_intensidad_if"],
                "ascenso_metros" => $entrenamiento["ascenso_metros"],
                "comentario" => $entrenamiento["comentario"],
            ];
        }

        return response()->json($res_entrenamientos, 200);
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
            'id_ciclista'          => 'required|exists:ciclistas,id',
            'id_bicicleta'         => 'nullable|exists:bicicletas,id',
            'id_sesion'            => 'nullable|exists:sesiones_entrenamientos,id',
            'fecha'                => 'required|date',
            'duracion'             => 'required|integer',
            'kilometros'           => 'required|numeric',
            'recorrido'            => 'nullable|string',
            'pulso_medio'          => 'nullable|integer',
            'pulso_max'            => 'nullable|integer',
            'potencia_media'       => 'nullable|integer',
            'potencia_normalizada' => 'nullable|integer',
            'velocidad_media'      => 'nullable|numeric',
            'puntos_estres_tss'    => 'nullable|numeric',
            'factor_intensidad_if' => 'nullable|numeric',
            'ascenso_metros'       => 'nullable|integer',
            'comentario'           => 'nullable|string',
        ]);

        $entrenamiento = Entrenamiento::create($validated);

        return response()->json([
            'message' => 'Actividad registrada correctamente',
            'data' => $entrenamiento
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
        $entrenamiento = Entrenamiento::with(['ciclista', 'bicicleta', "sesion"])->findOrFail($id);

        return response()->json($entrenamiento, 200);
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
}
