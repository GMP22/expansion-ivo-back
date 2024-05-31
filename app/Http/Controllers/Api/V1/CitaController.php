<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CitaAdministrativoResource;
use App\Http\Resources\V1\CitaMedicoResource;
use App\Models\Cita;
use App\Models\Diagnostico;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Http\Resources\V1\CitaRadiologoResource;
use Tymon\JWTAuth\Facades\JWTAuth;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

        $idPaciente = $request->input('id_usuario_paciente');
        $estado = $request->input('estado');
        
        // Obtener todas las citas asociadas al paciente
        $citas = CitaAdministrativoResource::collection(Cita::where([['id_usuario_paciente', "=", $idPaciente],
                                                                        ["estado", "LIKE", $estado]])->get());

        // Devolver las citas como JSON
        return response()->json($citas, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{

    $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

    // Validar los datos de la solicitud
    $request->validate([
        'id_usuario_paciente' => 'required|exists:pacientes,id_usuario_paciente',
        'hora' => 'required',
        'fecha' => 'required',
        'sip'=>'required',
        'id_servicio' => 'required',
        'id_usuario_medico' => '',
        'id_usuario_administrativo' => 'required',
        'id_usuario_radiologo' => ''

    ]);

    // Crear una nueva instancia de cita
    $cita = new Cita();
    $cita->id_usuario_paciente = $request->input('id_usuario_paciente');
    $cita->hora = $request->input('hora');
    $cita->fecha = $request->input('fecha');
    $cita->sip = $request->input('sip');
    $cita->id_servicio = $request->input('id_servicio');
    $cita->id_usuario_medico = $request->input('id_usuario_medico');
    $cita->id_usuario_administrativo = $request->input('id_usuario_administrativo');
    $cita->id_usuario_radiologo = $request->input('id_usuario_radiologo');


    // Guardar la cita
    $cita->save();
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

     public function articuloUsadoPorCita ($idMedico, $idArticulo){
        
                $citas = Cita::all() -> where("id_usuario_medico", $idMedico);
				$resultados = [];
				foreach($citas as $key => $value){
						$citaSeleccionada = $value -> articulosEnCita -> first();
						if($citaSeleccionada != null){
                            if ($value -> articulosEnCita -> first() -> pivot -> id_articulo == $idArticulo) {
                                $p = [
                                    "id_cita" => $citaSeleccionada -> pivot -> id_cita,
                                    "lotes_usados" => $citaSeleccionada -> pivot -> lotes_usados,
                                ];
                                $resultados [] = $p;
                            }
						}
				}
        return response()->json($resultados);
    }

    public function buscarCitaId($idCita){
		$cita = Cita::find($idCita);
		$usuario = Usuario::find($cita->id_usuario_paciente);
		$p = [
						'sip' => $cita ->sip,
            'nombre' => $usuario ->nombre,
            'apellido' => $usuario ->apellido1 . " " . $usuario ->apellido2,
            'hora' => $cita ->hora,
            'id_cita' => $cita ->id_cita,
            'estado' => $cita ->estado,
            'id_paciente' => $cita->id_usuario_paciente,
		];
		
		return response()->json($p);
        }

    public function update(Request $request, Cita $cita)
    {

        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

        // Validar los datos recibidos en la solicitud
        $request->validate([
            'id_servicio' => 'nullable|integer',
            'id_usuario_medico' => 'nullable|integer',
            'hora' => 'nullable|string',
            'fecha' => 'nullable|date',
        ]);

        // Actualizar los campos de la cita
        if ($request->filled('id_servicio')) {
            $cita->id_servicio = $request->id_servicio;
        }

        if ($request->filled('id_usuario_medico')) {
            $cita->id_usuario_medico = $request->id_usuario_medico;
        }

        if ($request->filled('hora')) {
            $cita->hora = $request->hora;
        }

        if ($request->filled('fecha')) {
            $cita->fecha = $request->fecha;
        }

        // Guardar los cambios en la base de datos
        $cita->save();

        // Devolver una respuesta exitosa
        return response()->json(['message' => 'Cita actualizada exitosamente'], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cita $cita)
    {   
        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }
       
        $cita->delete();
        return response()->json(null, 204);
    }

    public function getCitasRadiologo(String $fecha, int $id_radiologo){

    }

    public function getCitasMasRecientes(){

        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

        $citas = new Cita();

        $fechaActual = date("Y-m-d");

       $citas = CitaAdministrativoResource::collection(Cita::where([
                                            ['estado', '=', 'pendiente'],
                                            ['fecha', '>=', $fechaActual],
                                            ]) -> get());

       return response()->json($citas, 200);
    }


    public function getCitasPendientesRadiologo(String $fecha, int $id_radiologo){

        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

        $citas = CitaRadiologoResource::collection(Cita::where([
                                                    ['id_usuario_radiologo', '=', $id_radiologo],
                                                    ['fecha', '=', $fecha],
                                                    ['estado', 'like', '%pendiente%']
                                                    ])->get());
        // $citas = Cita::where([
        //                     ['id_usuario_radiologo', '=', $id_radiologo],
        //                     ['fecha', '=', $fecha],
        //                     ['estado', 'like', '%pendiente%']
        //                     ])->get();

    
        

        return response()->json(['citas' => $citas]);
    }
    public function getCitasRealizadaRadiologo(String $fecha, int $id_radiologo){
     
        $citas = CitaRadiologoResource::collection(Cita::where([
                                                    ['id_usuario_radiologo', '=', $id_radiologo],
                                                    ['fecha', '=', $fecha],
                                                    ['estado', 'like', '%realizada%']
                                                    ])->get());

        return response()->json(['citas' => $citas], 200);
    }

    public function getCitasPendientesMedico(String $fecha, int $id_medico){
       
        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

        $citas = CitaMedicoResource::collection(Cita::where([
                                                    ['id_usuario_medico', '=', $id_medico],
                                                    ['fecha', '=', $fecha],
                                                    ['estado', '=', 'pendiente']
                                                    ])->get());


        return response()->json($citas, 200);

    }

    public function getCitasRealizadasMedico(String $fecha, int $id_medico){
       
        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

            $citas = CitaMedicoResource::collection(Cita::where([
                ['id_usuario_medico', '=', $id_medico],
                ['fecha', '=', $fecha],
                ['estado', '=', 'realizada']
                ])->get());
        
        return response()->json($citas, 200);

    }

}
