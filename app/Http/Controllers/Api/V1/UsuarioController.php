<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\NombreUsuarioResource;
use App\Http\Resources\V1\UsuarioResource;
use App\Models\Usuario;
use Illuminate\Http\Request;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = UsuarioResource::collection(Usuario::all());
        return response()->json(['results' => $usuarios], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required',
            'nombre' => 'required',
            'apellido1'=>'required',
            'apellido2' => 'required',
            'Sexo' => 'required',
            'fecha_nacimiento' => 'required',
            'correo' => 'required',
            'telefono' => 'required',
            'codigo_postal' => 'required',
            'direccion' => 'required',
            'nombre_cuenta' => 'required',
            'password' => 'required',
            'id_rol' => 'required',
        ]);

        $usuario = new Usuario();

        $usuario -> dni = $request -> input('dni');
        $usuario -> nombre = $request -> input('nombre');
        $usuario -> apellido1 = $request -> input('apellido1');
        $usuario -> apellido2 = $request -> input('apellido2');
        $usuario -> Sexo = $request -> input('Sexo');
        $usuario -> fecha_nacimiento = $request -> input('fecha_nacimiento');
        $usuario -> correo = $request -> input('correo');
        $usuario -> telefono = $request -> input('telefono');
        $usuario -> codigo_postal = $request -> input('codigo_postal');
        $usuario -> direccion = $request -> input('direccion');
        $usuario -> nombre_cuenta = $request -> input('nombre_cuenta');
        $usuario -> password = $request -> input('password');
        $usuario -> id_rol = $request -> input('id_rol');    
        $usuario -> esJefe = false;
        $usuario->save();
        return response()->json(['message' => 'Usuario correctamente'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id_rol)
    {
        
        $usuario = NombreUsuarioResource::collection(Usuario::where('id_rol', $id_rol)->get());    
        
        return response()->json($usuario, 201);
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'dni' => 'nullable|string',
            'nombre' => 'nullable|string',
            'apellido1' => 'nullable|string',
            'apellido2' => 'nullable|string',
            'Sexo' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'codigo_postal' => 'nullable|string',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string',
            'correo' => 'nullable|email',
        ]);

        $usuario->update([
            'dni' => $request->has('dni') ? $request->dni : $usuario->dni,
            'nombre' => $request->has('nombre') ? $request->nombre : $usuario->nombre,
            'apellido1' => $request->has('apellido1') ? $request->apellido1 : $usuario->apellido1,
            'apellido2' => $request->has('apellido2') ? $request->apellido2 : $usuario->apellido2,
            'Sexo' => $request->has('Sexo') ? $request->Sexo : $usuario->Sexo,
            'fecha_nacimiento' => $request->has('fecha_nacimiento') ? $request->fecha_nacimiento : $usuario->fecha_nacimiento,
            'codigo_postal' => $request->has('codigo_postal') ? $request->codigo_postal : $usuario->codigo_postal,
            'direccion' => $request->has('direccion') ? $request->direccion : $usuario->direccion,
            'telefono' => $request->has('telefono') ? $request->telefono : $usuario->telefono,
            'correo' => $request->has('correo') ? $request->correo : $usuario->correo,
        ]);

        // Devolver una respuesta exitosa
        return response()->json(['message' => 'Datos del paciente actualizados correctamente'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        //
    }
}
