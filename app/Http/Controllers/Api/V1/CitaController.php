<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $idPaciente = $request->input('id_usuario_paciente');
        $estado = $request->input('estado');


        // Buscar todas las citas asociadas al paciente con el ID proporcionado
        $query = Cita::where('id_usuario_paciente', $idPaciente);

        if ($estado !== null) {
            $query->where('estado', $estado);
        }

        $citas = $query->get();

        // Retornar las citas como respuesta JSON
        return response()->json(['citas' => $citas], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validar los datos de la solicitud
    $request->validate([
        'id_usuario_paciente' => 'required|exists:pacientes,id_usuario_paciente',
        'hora' => 'required',
        'sip'=>'required',
        'id_servicio' => 'required',
        'id_usuario_medico' => '',
        'id_usuario_administrativo' => 'required',
        'id_usuario_radiologo' => ''
        // Agrega aquí las validaciones necesarias para los demás campos de la cita
    ]);

    // Crear una nueva instancia de Cita con los datos de la solicitud
    $cita = new Cita();
    $cita->id_usuario_paciente = $request->input('id_usuario_paciente');
    $cita->hora = $request->input('hora');
    $cita->sip = $request->input('sip');
    $cita->id_servicio = $request->input('id_servicio');
    $cita->id_usuario_medico = $request->input('id_usuario_medico');
    $cita->id_usuario_administrativo = $request->input('id_usuario_administrativo');
    $cita->id_usuario_radiologo = $request->input('id_usuario_radiologo');
    // Agrega aquí la asignación de los demás campos de la cita

    // Guardar la cita en la base de datos
    $cita->save();

    // Retornar una respuesta de éxito
    return response()->json(['message' => 'Cita creada correctamente'], 201);
}


    /**
     * Display the specified resource.
     */
    public function show(Cita $cita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cita $cita)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cita $cita)
    {
        $cita->delete();
        return response()->json(null, 204);
    }
    public function getCitasRadiologo(String $fecha, int $id_radiologo){

        $citas = Cita::where('id_usuario_paciente', $idPaciente);

        return response()->json(['citas' => $citas]);
    }
}
