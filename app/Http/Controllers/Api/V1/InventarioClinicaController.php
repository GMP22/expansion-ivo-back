<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventarioClinica;
use App\Models\CategoriaArticulos;
use App\Models\Usuario;
use App\Models\Proveedor;
use App\Models\Pedidos;
use App\Models\Medico;
use App\Models\AlmacenGeneral;

class InventarioClinicaController extends Controller
{
    

    

    public function index(){
        $inventario = InventarioClinica::all();
        $articulosResultantes = [];
            foreach ($inventario as $key => $value) {
                $nombreArticulo = $value -> articulo -> nombre;
                $categoria = $value -> articulo -> categoria -> nombre_categoria;
                $lotesDisponibles = $value -> lotes_disponibles;

                $pedidos = $value -> articulo -> pedidos -> where('es_departamento', '=', false) -> where('estado', '=', "Recibido");
                $fechas = [];
                    foreach ($pedidos as $key2 => $value2) {
                            if ($value2 -> usuario -> rol -> id_rol == 6) { // numero de rol del gestor logistico
                                $fechas [] = $value2 -> fecha_aceptada;
                            }
                    }
                
                $a = [
                    'id_articulo_clinica' => $value -> id_articulo_clinica,
                    'nombre_articulo' => $nombreArticulo,
                    'nombre_categoria' => $categoria,
                    'numero_lotes' => $lotesDisponibles,
                    'estado' => $value -> estado,
                    'ultima_fecha_recibida' => $fechas[array_key_last($fechas)],
                ];
                $articulosResultantes[] = $a;
            }
        return response()->json($articulosResultantes);
    }

    public function indexMedico($idMedico){
        $inventario = InventarioClinica::all();
        $articulosResultantes = [];

        foreach ($inventario as $key => $value) {
            $a = $value->inventarioMedicos->where("id_usuario_medico", $idMedico)->first();

            if ($a != null) {
                $nombreArticulo = $value -> articulo -> nombre;
                $categoria = $value -> articulo -> categoria -> nombre_categoria;

                $pedidos = $value -> articulo -> pedidos -> where('estado', "Aceptada") -> where('id_usuario_solicitante', $idMedico) -> where("es_departamento", false);
                $fechas = [];

                    foreach ($pedidos as $key2 => $value2) {
                            if ($value2 -> usuario -> rol -> id_rol == 2) { // numero de rol del medico
                                $fechas [] = $value2 -> fecha_aceptada;
                            }
                    }

                $p = [
                    'id_articulo_clinica' => $a -> pivot -> id_articulo_departamento,
                    'nombre_articulo' => $nombreArticulo,
                    'nombre_categoria' => $categoria,
                    'numero_lotes' => $a -> pivot -> lotes_disponibles,
                    'estado' => $a -> pivot -> estado,
                    'ultima_fecha_recibida' => $fechas[array_key_last($fechas)],
                ];

                $articulosResultantes[] = $p;
            }
        }

        return response()->json($articulosResultantes);
    }

    public function numeroMinimosGestor(){
        $numero = InventarioClinica::where("estado", "En Minimos")->get()->count();
        return response()->json($numero, 200); 
    }

    public function numeroArticulosAutomaticos(){
        $numero = InventarioClinica::where("pedido_automatico", true)->get()->count();
        return response()->json($numero, 200); 
    }

    public function articulosAutomaticos(){
        $articulosAutomaticos = InventarioClinica::where("pedido_automatico", true)->get(["id_articulo"]);
        $nombres = [];
        foreach ($articulosAutomaticos as $key => $value) {
            $p = [
                'id_articulo' => $value -> id_articulo,
                'nombre' => AlmacenGeneral::find($value -> id_articulo)->nombre,
            ];
            $nombres[] = $p;
        }

        return response()->json($nombres, 200); 
    }

    public function articulosMinimosGestor(){
        $articulosMinimos = InventarioClinica::where("estado", "En Minimos")->get();
        $nombres = [];
        foreach ($articulosMinimos as $key => $value) {
            $p = [
                'id_articulo' => $value -> id_articulo,
                'nombre' => AlmacenGeneral::find($value -> id_articulo)->nombre,
            ];
            $nombres[] = $p;
        }
        return response()->json($nombres, 200); 
    }

    public function detallesArticuloGestor($id){
        $articulo = collect(InventarioClinica::find($id));
        
        $info = AlmacenGeneral::find($articulo["id_articulo"]);

        $articulo -> put('nombre', $info->nombre);
        return response()->json($articulo, 200); 
    }

    public function detallesArticuloMedico($idUsuario, $idArticulo){
        $articulo = collect(InventarioClinica::find($idArticulo)->inventarioMedicos->where("id_usuario_medico", $idUsuario)->first()->pivot);
        
        $info = AlmacenGeneral::find(InventarioClinica::find($idArticulo)->id_articulo);

        $articulo -> put('nombre', $info->nombre);
        return response()->json($articulo, 200); 
    }

    public function pedidosConArticuloEspecifico($id){
        $pedidos=InventarioClinica::find($id)->articulo->pedidos->where("es_departamento", false)->where("estado", "Recibido");
        
        $p = [];
        foreach ($pedidos as $key => $value) {

            $proveedor = Proveedor::find($value->pivot->id_proveedor);
                $x = [
                    "id_pedido" => $value -> id_pedido,
                    "id_proveedor" => $proveedor -> id_proveedor,
                    "nombre" => $proveedor -> nombre,
                    "lotes_recibidos" => $value -> pivot -> lotes_recibidos,
                ];
                $p [] = $x;
        }
        return response()->json($p, 200);
    }

    public function pedidosConArticuloEspecificoMedico($idUsuario, $idArticulo){
        $pedidos=InventarioClinica::find($idArticulo)->articulo->pedidos->where("es_departamento", false)->where("estado", "Aceptada")->where("id_usuario_solicitante", $idUsuario);
        $p = [];
        foreach ($pedidos as $key => $value) {
                $x = [
                    "id_pedido" => $value -> id_pedido,
                    "id_proveedor" => null,
                    "nombre" => null,
                    "lotes_recibidos" => $value -> pivot -> lotes_recibidos,
                ];
                $p [] = $x;
        }
        return response()->json($p, 200);
    }

    public function detallesPedidoEspecifico($id){
        $pedido=Pedidos::find($id);
        $pedidosResultantes = [];
        $numero_productos = null;
        $proveedor = Proveedor::find($pedido->proveedores[0]->id_proveedor);

            $articulos = $pedido->articulos;
            $costeArticulos = 0;

            $pedidosResultantes = [];
                foreach ($articulos as $key2 => $value2) {
                   $costeArticulos += $value2->pivot->lotes_recibidos * $value2->proveedores[0]->pivot->coste_por_lote;
                }
            $numero_productos = $pedido->articulos->count(); 

            $p = [
                'id_pedido' => $pedido->id_pedido,
                'proveedor' => $proveedor->nombre,
                'estado' => $pedido -> estado,
                'fecha_inicial' => $pedido->fecha_inicial,
                'numero_productos' => $numero_productos,
                'coste' => $costeArticulos, 
            ];
           
            $pedidosResultantes[] = $p;
            
        return response()->json($p, 200);
    }

    public function cambiarMinimos($id, Request $request){
        
        $articulo = InventarioClinica::find($id);
        $articulo -> stock_minimo = $request[0];

        if ($articulo -> stock_minimo > $articulo -> lotes_disponibles) {
            $articulo -> estado = "En Minimos";
        } else {
            $articulo -> estado = "En Stock";
        }

        $articulo -> save();
        return response()->json($articulo -> estado, 200);
    }

    public function nuevaFunctionAutomatica(Request $request){
        $articulo = AlmacenGeneral::find($request->id_articulo);
        $texto = "hola";
        if ($articulo -> articuloConPedidosAutomaticos->where('id_usuario', $request->id_usuario)->count() == 0) {
            $articulo -> articuloConPedidosAutomaticos() -> attach($request->id_articulo, ["id_usuario"=>$request->id_usuario, "id_proveedor"=>$request->id_proveedor, "stock_a_pedir"=>$request->cantidad]);
            $texto = "creado";
        } else {
            $articulo -> articuloConPedidosAutomaticos() -> updateExistingPivot($request->id_usuario, ["id_proveedor"=>$request->id_proveedor, "stock_a_pedir"=>$request->cantidad]);
            $texto = "actualizado";
        }
        return response()->json($texto, 200);
    }

    public function cambiarMinimosMedico($idUsuario, $idArticulo, Request $request){
        
        $articulo = InventarioClinica::find($idArticulo)->inventarioMedicos->where("id_usuario_medico", $idUsuario)->first()->pivot;
        $articulo -> stock_minimo = $request[0];

        if ($articulo -> stock_minimo > $articulo -> lotes_disponibles) {
            $articulo -> estado = "En Minimos";
        } else {
            $articulo -> estado = "En Stock";
        }

        $articulo -> save();
        return response()->json($articulo -> estado, 200);
    }

    public function nuevaFunctionAutomaticaMedico(Request $request){
        $articulo = InventarioClinica::find($request->id_articulo)->articulo->first();
        $texto = "hola";
        if ($articulo -> articuloConPedidosAutomaticos->where('id_usuario', $request->id_usuario)->count() == 0) {
            $articulo -> articuloConPedidosAutomaticos() -> attach($articulo->id_articulo, ["id_usuario"=>$request->id_usuario, "id_proveedor"=>null, "stock_a_pedir"=>$request->cantidad]);
            InventarioClinica::find($request->id_articulo)->inventarioMedicos()->updateExistingPivot($request->id_usuario, ["pedido_automatico" => true]);
            $texto = "creado";
        } else {
            InventarioClinica::find($request->id_articulo)->inventarioMedicos()->updateExistingPivot($request->id_usuario, ["pedido_automatico" => true]);
            $articulo -> articuloConPedidosAutomaticos() -> updateExistingPivot($request->id_usuario, ["stock_a_pedir"=>$request->cantidad]);
            $texto = "actualizado";
        }
        return response()->json($texto, 200);
    }

    public function eliminarFuncionAutomaticaMedico(Request $request){
        $articulo = InventarioClinica::find($request->id_articulo)->articulo->first();
        $articulo -> articuloConPedidosAutomaticos() -> detach($request->id_usuario);
        InventarioClinica::find($request->id_articulo)->inventarioMedicos()->updateExistingPivot($request->id_usuario, ["pedido_automatico" => false]);
        return response()->json("Eliminado exitosamente el pedido automatico", 200);
    }       

    public function eliminarFuncionAutomatica(Request $request){
        $articulo = AlmacenGeneral::find($request->id_articulo);
        $articulo -> articuloConPedidosAutomaticos() -> detach($request->id_usuario);
        return response()->json("Eliminado exitosamente el pedido automatico", 200);
    }        

    public function articulosMinimosSolicitud($idPedido){
        $pedido = Pedidos::find($idPedido);
        $articulos = $pedido -> articulos;
        $rdo = [];
        foreach ($articulos as $key => $value) {
            $articuloClinica = InventarioClinica::where("id_articulo", $value->id_articulo)->first();
            if ($articuloClinica->estado == "En Minimos" || $value -> pivot -> lotes_recibidos > $articuloClinica->lotes_disponibles) {
                $p = [
                    "id_articulo" => $value->id_articulo,
                    "nombre_articulo" => $value->nombre,
                    "lotes_disponibles" => $articuloClinica->lotes_disponibles,
                ];
                $rdo [] = $p;
            }
        }
        return response()->json($rdo, 200);
    }

    public function cuadrosInformativosInventarioMedico($idMedico){
		$articulosAutomatico = Medico::find($idMedico) -> articulosMedicos;
		$articulosMinimos = Medico::find($idMedico) -> articulosMedicos;
        $cantidad1 = 0;
        $cantidad2 = 0;

            foreach ($articulosAutomatico as $key => $value) {
                
                if ($value -> pivot -> pedido_automatico == true) {
                    $cantidad1++;
                }

            }

            foreach ($articulosMinimos as $key2 => $value2) {
                if ($value2 -> pivot -> estado == "En Minimos") {
                    $cantidad2++;
                }
            }

			$p = [
			"articulos_automaticos" => $cantidad1,
			"articulos_minimos" => $cantidad2,
			];
			
			return response()->json($p);
        }

        public function articulosMinimosMedico($idMedico){
            $articulosMinimos = InventarioClinica::all();
            $nombres = [];
            foreach ($articulosMinimos as $key => $value) {
    
                if($value -> inventarioMedicos -> where("id_usuario_medico", $idMedico) -> first() != null){
                    $articuloSeleccionado = $value -> inventarioMedicos -> where("id_usuario_medico", $idMedico) -> first() -> pivot;
                    if($articuloSeleccionado->estado == "En Minimos"){
                         $p = [
                            'id_articulo' => $articuloSeleccionado -> id_articulo_departamento,
                            'nombre' => AlmacenGeneral::find($value -> id_articulo)->nombre,
                            'nombre_categoria' => AlmacenGeneral::find($value -> id_articulo)->categoria->first()->nombre_categoria,
                        ];
                         $nombres[] = $p;
                    } 
                }
            }
            return response()->json($nombres, 200); 
        }

        public function articulosAutomaticosMedico($idMedico){
            $articulosMinimos = InventarioClinica::all();
            $nombres = [];
            foreach ($articulosMinimos as $key => $value) {
    
                if($value -> inventarioMedicos -> where("id_usuario_medico", $idMedico) -> first() != null){
                    $articuloSeleccionado = $value -> inventarioMedicos -> where("id_usuario_medico", $idMedico) -> first() -> pivot;
                    
                    if($articuloSeleccionado->pedido_automatico == true){
                    
                         $p = [
                            'id_articulo' => $articuloSeleccionado -> id_articulo_departamento,
                            'nombre' => AlmacenGeneral::find($value -> id_articulo)->nombre,
                            'nombre_categoria' => AlmacenGeneral::find($value -> id_articulo)->categoria->first()->nombre_categoria,
                        ];
                         $nombres[] = $p;
                    } 
    
                }
            }
            return response()->json($nombres, 200); 
        }
}
