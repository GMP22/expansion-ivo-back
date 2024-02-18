<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class ServicioController extends Controller
{
   
    public function addServicio(){
        $breadcrumbs = [
            ['volver' => 'Volver', 'routa-volver' => route('gestor.servicio')],
            ['nav-opcion-1' => 'Servicios', 'routa-opcion-1' => route('gestor.servicio')],
            ['nav-opcion-2' => 'Alta servicio', 'routa-opcion-2' => null]
        ];
        return view('usuario.gestor.servicio.addServicio', compact('breadcrumbs'));
    }
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicios = Servicio::all();
        return view('usuario.gestor.servicio.servicio', compact('servicios'));
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
            'nombre_servicio.required' => 'El campo nombre rol es obligatorio.',
            'nombre_servicio.string' => 'El nombre rol debe ser una cadena de texto.',
            'nombre_servicio.max' => 'El nombre rol no debe ser mayor a 255 caracteres.',
            'fecha_creacion.required' => 'El campo fecha creación es obligatorio.',
            'fecha_creacion.date' => 'La fecha de creacion no tiene un formato válido.',
        ];
        $validator = Validator::make($request->all(), [
            'nombre_servicio' => 'required|string|max:255',
            'fecha_creacion' => 'required|date',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('gestor.add-servicio')
                        ->withErrors($validator)
                        ->withInput();
        }
        $validatedData = $validator->validated();

        $servicio = Servicio::create([
            'nombre_servicio' => $validatedData['nombre_servicio'],
            'fecha_creacion' => $validatedData['fecha_creacion'],
            'id_usuario_gestor' =>  Auth::guard('usuario')->user()->id_usuario,
        ]);
        
        return redirect()->route('gestor.add-servicio')->with('success', 'Servicio creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Servicio $servicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $breadcrumbs = [
            ['volver' => 'Volver', 'routa-volver' => route('gestor.servicio')],
            ['nav-opcion-1' => 'Servicios', 'routa-opcion-1' => route('gestor.servicio')],
            ['nav-opcion-2' => 'Alta servicio', 'routa-opcion-2' => null]
        ];
        $servicio = Servicio::findOrFail($id);
        return view('usuario.gestor.servicio.edit', compact('servicio', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $messages = [
            'nombre_servicio.required' => 'El campo nombre rol es obligatorio.',
            'nombre_servicio.string' => 'El nombre rol debe ser una cadena de texto.',
            'nombre_servicio.max' => 'El nombre rol no debe ser mayor a 255 caracteres.',
        ];
        $validator = Validator::make($request->all(), [
            'nombre_servicio' => 'required|string|max:255',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('servicio.edit'  , ['id' => $id] )
                        ->withErrors($validator)
                        ->withInput();
        }
        $validatedData = $validator->validated();

        $servicio = Servicio::find($id);

        $servicio->update([
            'nombre_servicio' => $validatedData['nombre_servicio'],
        ]);
        
        return redirect()->route('gestor.servicio')->with('success', 'Servicio modificado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servicio $servicio)
    {
        //
    }
}
