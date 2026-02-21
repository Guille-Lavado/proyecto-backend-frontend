<?php

namespace App\Http\Controllers\Api;

use App\Models\Ciclista;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   public function register(Request $request)
    {
        // 1. Validar los datos recibidos desde el Frontend (V2)
        // Ahora validamos unique:users en lugar de unique:ciclistas
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', 
            'password' => 'required|string|min:6',
            'fecha_nacimiento' => 'required|date',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric',
        ]);

        // 2. Crear el Usuario para la Autenticación (Tabla 'users')
        $user = User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        // 3. Crear el Perfil Deportivo (Tabla 'ciclistas')
        // Mapeamos los datos del frontend a los nombres de columna de tu tabla
        \App\Models\Ciclista::create([ // Cambia \App\Ciclista por \App\Models\Ciclista si usas carpeta Models
            'id_user' => $user->id,
            'apellido' => $request->apellidos,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'peso_base' => $request->peso,
            'altura_base' => $request->altura,
        ]);

        // 4. Iniciar sesión automáticamente devolviendo el Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario y Perfil de Ciclista registrados con éxito',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        // 1. Validar petición
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::query()->where('email', $request->email)->first();

        // 2. Verificar si existe y si la contraseña es correcta
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                "status" => "Error",
                "message" => "Las credenciales son incorrectas."
            ], 422);
        }

        // 3. Crear el token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Crear respuesta
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // Borramos el token actual
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}