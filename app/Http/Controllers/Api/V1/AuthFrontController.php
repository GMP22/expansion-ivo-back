<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthFrontController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login()
    {
        $credentials = request(['correo', 'password']);
        //$credentials['password'] = bcrypt($credentials['password']);

        if (! $token = auth() ->attempt($credentials)) {
            return response()->json(['error' => $credentials["password"]], 401);
        }

        $user = auth()->user(); // Obtener el usuario autenticado
        $customClaims = [
            'nombre' => $user->nombre,
            'apellido1' => $user->apellido1,
            'correo' => $user->correo,
        ];

        $token = auth()->claims($customClaims)->attempt($credentials);
                             
        return response()->json([
            'token' => $token,
            'user' => $user // Devolver todos los datos del usuario
            /*'user' => [
                'id_usuario' => $user->id_usuario, // Obtener el ID del usuario
                'id_rol' => $user->id_rol, // Obtener el ID del rol del usuario
            ]*/
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['mensaje' => 'Cierre de sesión exitoso']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 120
        ]);
    }

    
}
