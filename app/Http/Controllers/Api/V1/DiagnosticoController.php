<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\DiagnosticoResource;
use App\Models\Diagnostico;
use App\Models\Paciente;
use App\Models\Cita;
use App\Models\Pedidos;
use App\Models\InventarioClinica;
use Illuminate\Http\Request;

class DiagnosticoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $request->validate([
            'informe' => 'required',
            'tratamientos' => 'required',
            'fecha_creacion'=>'required',
            'id_medico' => 'required',
            'sip' => 'required',
        ]);

        $citaDelPaciente = new Cita();
        
        $sip = $request -> input('sip');
        $pacientes = Paciente::where('sip', $sip) -> first();

        $diagnostico = new Diagnostico();

        $diagnostico->informe=$request->input('informe');
        $diagnostico->tratamiento=$request->input('tratamientos');
        $diagnostico->fecha_creacion=$request->input('fecha_creacion');
        $diagnostico->id_medico=$request->input('id_medico');
        $diagnostico->id_paciente=$pacientes->id_usuario_paciente;
        $diagnostico->id_cita=$request->input('id_cita');
        $diagnostico->save();
        

        $citaDelPaciente = Cita::where('id_cita', $request->input('id_cita')) -> first();
        $citaDelPaciente -> estado = 'realizada';

        $citaDelPaciente -> save();
        

        return response()->json(['message' => 'Diagnostico creado correctamente'], 201);
    }

    public function inventarioMedicoCita ($idCita){

        $citaDelPaciente = Cita::find($idCita);

        $articulosEnCitas = $citaDelPaciente -> articulosEnCita;

        $inventario = InventarioClinica::all();
        $articulosResultantes = [];

        foreach ($inventario as $key => $value) {
            $a = $value->inventarioMedicos->where("id_usuario_medico", $citaDelPaciente->id_usuario_medico)->first();

            if ($a != null) { // Esto se hace para verificar que efectivamente el medico en cuestion TIENE ese articulo
                $nombreArticulo = $value -> articulo -> nombre;
                $categoria = $value -> articulo -> categoria -> nombre_categoria;
                $cantidadASacar = 0;

                    foreach ($articulosEnCitas as $key2 => $value2) { // Aqui buscamos que articulos fueron usados en la cita
                        if ($value2 -> pivot -> id_articulo == $value -> id_articulo_clinica) {
                            $cantidadASacar = $value2 -> pivot -> lotes_usados;
                        }
                    }

                $p = [
                    'id_articulo_clinica' => $a -> pivot -> id_articulo_departamento,
                    'nombre_articulo' => $nombreArticulo,
                    'nombre_categoria' => $categoria,
                    'numero_lotes' => $cantidadASacar,
                    'estado' => $a->pivot->lotes_disponibles,
                    'ultima_fecha_recibida' => "null",
                ];

                $articulosResultantes[] = $p;
            }
        }

        return response()->json($articulosResultantes);

    }

    public function registrarArticulosCita($idCita, Request $request){

        $citaDelPaciente = Cita::find($idCita);
        $articulos = $request->all();
       
            foreach ($articulos as $key => $value) {
                $lotesDisponibles = InventarioClinica::find($value["id_articulo_clinica"])->inventarioMedicos->where("id_usuario_medico", $citaDelPaciente->id_usuario_medico)->first()->pivot->lotes_disponibles;
                InventarioClinica::find($value["id_articulo_clinica"])->inventarioMedicos()->updateExistingPivot($citaDelPaciente->id_usuario_medico,["lotes_disponibles" => $lotesDisponibles - $value["numero_lotes"]]);
                $citaDelPaciente->articulosEnCita() -> attach($idCita, ["id_articulo" => $value["id_articulo_clinica"], "lotes_usados" => $value["numero_lotes"]]);

                $articuloSeleccionado = InventarioClinica::find($value["id_articulo_clinica"])->inventarioMedicos->where("id_usuario_medico",$citaDelPaciente->id_usuario_medico)->first();

                if ($articuloSeleccionado->pivot->lotes_disponibles < $articuloSeleccionado->pivot->stock_minimo) {
                    InventarioClinica::find($value["id_articulo_clinica"])->inventarioMedicos()->updateExistingPivot($citaDelPaciente->id_usuario_medico,["estado" => "En Minimos"]);

                    if ($articuloSeleccionado -> pivot-> pedido_automatico == true) {
                        $pedidoNuevo = new Pedidos();
                        $pedidoNuevo -> id_usuario_solicitante = $citaDelPaciente->id_usuario_medico;
                        $pedidoNuevo -> fecha_inicial = date('Y-m-d');
                        $pedidoNuevo -> fecha_aceptada = null;
                        $pedidoNuevo -> estado = "Pendiente";
                        $pedidoNuevo -> es_departamento = false;
                        $pedidoNuevo -> id_servicio = 6;
                        $cantidadAPedir = InventarioClinica::find($value["id_articulo_clinica"]) -> articulo -> first() -> articuloConPedidosAutomaticos -> where("id_usuario", $citaDelPaciente->id_usuario_medico) -> first() -> pivot -> stock_a_pedir;
                        $pedidoNuevo -> save();
                        $pedidoNuevo -> articulos() -> attach(InventarioClinica::find($value["id_articulo_clinica"])->articulo->id_articulo, ["id_proveedor" => null, "lotes_recibidos" => $cantidadAPedir]);
                    }
                }
            }
            
        return response()->json($citaDelPaciente->articulosEnCita, 201);
    }

    public function modificarArticulosCita($idCita, Request $request){

        $citaDelPaciente = Cita::find($idCita);

        $articulosAnteriormenteMetidos = $citaDelPaciente -> articulosEnCita;

            foreach ($articulosAnteriormenteMetidos as $key => $value) {
                $lotesDisponibles = InventarioClinica::find($value->id_articulo_clinica)->inventarioMedicos->where("id_usuario_medico", $citaDelPaciente->id_usuario_medico)->first()->pivot->lotes_disponibles;
                InventarioClinica::find($value->id_articulo_clinica)->inventarioMedicos()->updateExistingPivot($citaDelPaciente->id_usuario_medico,["lotes_disponibles" => $lotesDisponibles + $value->pivot->lotes_usados]);

                $articuloSeleccionado = InventarioClinica::find($value->id_articulo_clinica)->inventarioMedicos->where("id_usuario_medico",$citaDelPaciente->id_usuario_medico)->first();

                if ($articuloSeleccionado->pivot->lotes_disponibles < $articuloSeleccionado->pivot->stock_minimo) {
                    InventarioClinica::find($value->id_articulo_clinica)->inventarioMedicos()->updateExistingPivot($citaDelPaciente->id_usuario_medico,["estado" => "En Minimos"]);
                } else {
                    InventarioClinica::find($value->id_articulo_clinica)->inventarioMedicos()->updateExistingPivot($citaDelPaciente->id_usuario_medico,["estado" => "En Stock"]);
                }
            }

        $citaDelPaciente->articulosEnCita()->detach();
        $articulos = $request->all();
        
            foreach ($articulos as $key => $value2) {
                $lotesDisponibles2 = InventarioClinica::find($value2["id_articulo_clinica"])->inventarioMedicos->where("id_usuario_medico", $citaDelPaciente->id_usuario_medico)->first()->pivot->lotes_disponibles;
                InventarioClinica::find($value2["id_articulo_clinica"])->inventarioMedicos()->updateExistingPivot($citaDelPaciente->id_usuario_medico,["lotes_disponibles" => $lotesDisponibles2 - $value2["numero_lotes"]]);
                $citaDelPaciente->articulosEnCita() -> attach($idCita, ["id_articulo" => $value2["id_articulo_clinica"], "lotes_usados" => $value2["numero_lotes"]]);

                $articuloSeleccionado = InventarioClinica::find($value2["id_articulo_clinica"])->inventarioMedicos->where("id_usuario_medico",$citaDelPaciente->id_usuario_medico)->first();

                if ($articuloSeleccionado->pivot->lotes_disponibles < $articuloSeleccionado->pivot->stock_minimo) {
                    InventarioClinica::find($value2["id_articulo_clinica"])->inventarioMedicos()->updateExistingPivot($citaDelPaciente->id_usuario_medico,["estado" => "En Minimos"]);
                }
            }

        return response()->json($citaDelPaciente->articulosEnCita, 201);
    }

    public function mostrarDiagnostico($idCita){

        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

        $diagnostico = new Diagnostico();

        $diagnostico = Diagnostico::where('id_cita', $idCita) -> first();

        return response()->json($diagnostico, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Diagnostico $diagnostico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

        $request->validate([
            'informe' => 'required',
            'tratamiento' => 'required',
           
        ]);

        $diagnostico = Diagnostico::where('id_cita', $id) -> first();
        
        $diagnostico->update([
            'informe' => $request-> input('informe'),
            'tratamiento' => $request-> input('tratamiento'),
        ]);
        
        return response()->json(['message' =>  $diagnostico], 200);
        
    }

    public function mostrarVolante($idCita){

        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

        $diagnostico = new Diagnostico();

        $diagnostico = Diagnostico::where('id_cita', $idCita) -> first();

        $volante = '';

        if($diagnostico != null){
            if($diagnostico -> volante != null){
                $volante = $diagnostico -> volante;
            }
        }

        return response()->json($volante, 201);
    }

    public function actualizarVolante(Request $request, $id)
    {   

        $user = $this->autentificarTokenJWT();
        
        if ($user -> original) {
            return response()->json($user);
        }

        $request->validate([
            'volante' => 'required',
        ]);

        $diagnostico = Diagnostico::where('id_cita', $id) -> first();
        
        $diagnostico->update([
            'volante' => $request-> input('volante'),
        ]);
        
        return response()->json(['message' =>  $diagnostico], 200);
        
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diagnostico $diagnostico)
    {
        //
    }
}
