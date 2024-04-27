<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolController extends Controller
{
    public function addRol(){
        $breadcrumbs = [
            ['volver' => 'Volver', 'routa-volver' => route('gestor.rol')],
            ['nav-opcion-1' => 'Rol', 'routa-opcion-1' => route('gestor.rol')],
            ['nav-opcion-2' => 'Alta Rol', 'routa-opcion-2' => null]
        ];
        return view('usuario.gestor.rol.addRol', compact('breadcrumbs'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rols = Rol::all()->except(0);;
        return view('usuario.gestor.rol.rol', compact('rols'));
    }
   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $messages = [
            'nombre_rol.required' => 'El campo nombre rol es obligatorio.',
            'nombre_rol.string' => 'El nombre rol debe ser una cadena de texto.',
            'nombre_rol.max' => 'El nombre rol no debe ser mayor a 255 caracteres.',
            'fecha_creacion.required' => 'El campo fecha creación es obligatorio.',
            'fecha_creacion.date' => 'La fecha de creacion no tiene un formato válido.',
        ];
        $validator = Validator::make($request->all(), [
            'nombre_rol' => 'required|string|max:255',
            'fecha_creacion' => 'required|date',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('gestor.add-rol')
                        ->withErrors($validator)
                        ->withInput();
        }
        $validatedData = $validator->validated();

        $rol = Rol::create([
            'nombre' => $validatedData['nombre_rol'],
            'fecha_creacion' => $validatedData['fecha_creacion'],
        ]);
        
        return redirect()->route('gestor.rol')->with('success', 'Rol creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rol $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $breadcrumbs = [
            ['volver' => 'Volver', 'routa-volver' => route('gestor.rol')],
            ['nav-opcion-1' => 'Rol', 'routa-opcion-1' => route('gestor.rol')],
            ['nav-opcion-2' => 'Modificar Rol', 'routa-opcion-2' => null]
        ];
        $rol = Rol::findOrFail($id);
        return view('usuario.gestor.rol.edit', compact('rol', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
 
        $messages = [
            'nombre_rol.required' => 'El campo nombre rol es obligatorio.',
            'nombre_rol.string' => 'El nombre rol debe ser una cadena de texto.',
            'nombre_rol.max' => 'El nombre rol no debe ser mayor a 255 caracteres.',
        ];
        $validator = Validator::make($request->all(), [
            'nombre_rol' => 'required|string|max:255',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('rol.edit'  , ['id' => $id])
                        ->withErrors($validator)
                        ->withInput();
        }
        $validatedData = $validator->validated();


        $rol = Rol::find($id);

        $rol->update([
            'nombre' => $validatedData['nombre_rol'],
        ]);
       
        return redirect()->route('gestor.rol')->with('success', 'Rol modificado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rol $rol)
    {
        //
    }
}
