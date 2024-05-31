<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventarioClinica;
use App\Models\Usuario;
use App\Models\Pedidos;
use App\Models\Servicio;
use App\Models\AlmacenGeneral;
class JefeDepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function plantilla()
    {
        return view('layouts.plantilla');
    }

    public function solicitudes($idServicio){
	
        $solicitudesPendientes = Pedidos::where("id_servicio", $idServicio)->where("estado", "Pendiente")->where("es_departamento", false)->get();
        $solicitudesAceptadas = Pedidos::where("id_servicio", $idServicio)->where("estado", "Aceptada")->where("es_departamento", false)->get();
        
        $rdo = [];
        $rdo2 = [];
        
        foreach($solicitudesPendientes as $key => $value){
            $p = [
                "id_pedido" => $value -> id_pedido,
                "nombre_usuario" => $value -> usuario -> nombre. " " . $value -> usuario -> apellido1,
                "numero_productos" => $value -> articulos -> count(),
                "fecha_inicial" => $value -> fecha_inicial,
            ];
            
            $rdo [] = $p;
        }
        
        foreach($solicitudesAceptadas as $key2 => $value2){
            $p2 = [
                "id_pedido" => $value2-> id_pedido,
                "nombre_usuario" => $value2-> usuario -> nombre . " " . $value2-> usuario -> apellido1,
                "numero_productos" => $value2-> articulos -> count(),
                "fecha_inicial" => $value2-> fecha_inicial,
                "fecha_aceptada" => $value2->fecha_aceptada,
            ];
            $rdo2 [] = $p2;
        }
        
        return view('solicitudes', compact('rdo', 'rdo2'));
    }

    public function verificarMinimosArticulos($idServicio, $idPedido){
        $pedido = Pedidos::find($idPedido);
        $articulos = $pedido -> articulos;
        $minimos = [];
        $cantidad = 0;

        foreach ($articulos as $key => $value) {
        $articuloClinica = InventarioClinica::where("id_articulo", $value->id_articulo) -> first() -> inventarioDepartamentos -> where("id_servicio", $idServicio)->first();

        if ($articuloClinica != null) {
            if ($articuloClinica->pivot->estado == "En Minimos" || $value -> pivot -> lotes_recibidos > $articuloClinica->pivot->lotes_disponibles) {
                $xd = 0;
                
                if ($value -> pivot -> lotes_recibidos >= $articuloClinica->pivot->lotes_disponibles) {
                    $xd = $articuloClinica->pivot->lotes_disponibles;
                } else if ($value -> pivot -> lotes_recibidos <= $articuloClinica->pivot->lotes_disponibles){
                    $xd = $value["pivot"]->lotes_recibidos;
                }

                $p = [
                    "id_articulo" => $value->id_articulo,
                    "nombre_articulo" => $value->nombre,
                    "lotes_disponibles" => $xd,
                ];
                $minimos [] = $p;
            $cantidad++;
                }
            }
        }
        $minimos [] = $idPedido;
    return response()->json($minimos, 200);
    }

    public function pedidos($id_servicio){   
        $pedidosPendientes = Pedidos::where("estado", "Pendiente")->where("id_servicio", $id_servicio)->where("es_departamento", true)->get();
        $pedidosAceptados = Pedidos::where("estado", "Aceptada")->where("id_servicio", $id_servicio)->where("es_departamento", true)->get();
        $rdo1 = []; 
        $rdo2 = [];

        foreach ($pedidosPendientes as $key => $value) {
            $p = [
                'id_pedido' => $value->id_pedido,
                'numero_productos'=> $value->articulos->count(),
                'fecha_inicial' => $value->fecha_inicial,
            ];
            $rdo1 [] = $p;
        }

        foreach ($pedidosAceptados as $key2 => $value2) {
            $a = [
                'id_pedido' => $value2->id_pedido,
                'numero_productos'=> $value2->articulos->count(),
                'fecha_inicial' => $value2->fecha_inicial,
                'fecha_aceptada' => $value2->fecha_aceptada,
            ];
            $rdo2 [] = $a;
        }

        //{{Auth::guard('usuario')->user()->servicio->id_servicio}}
        return view('pedidos', compact('rdo1', 'rdo2'));
    }

    public function crearPedidos(){   
        $articulos = InventarioClinica::all();

        $rdo = [];
        foreach ($articulos as $key => $value) {
            $p = [
                "id_articulo" => $value->id_articulo,
                "nombre" => $value->articulo->nombre,
            ];
            $rdo [] = $p;
        }

        return view('components.crear-pedido', compact("rdo"));
    }
   
    public function detallesPedido($idServicio, $idPedido){
        $pedido = Pedidos::find($idPedido);
        $numero_productos = $pedido->articulos->count();
        $usuario = $pedido->usuario->nombre." ".$pedido->usuario->apellido1;
        $fecha_inicial = $pedido->fecha_inicial;
        $fecha_aceptada = $pedido->fecha_aceptada;

        $rdo = [
            'id_pedido' => $pedido->id_pedido,
            'nombre_departamento' => $pedido->servicio->nombre_servicio,
            'nombre_jefe' => $usuario,
            'numero_productos' => $numero_productos,
            'fecha_inicial' => $fecha_inicial,
            'fecha_aceptada' => $fecha_aceptada,
        ];
        
        $articulos = $pedido -> articulos;
        
        $detalles = [];

        $breadcrumbs = [
            ['volver' => 'Volver', 'routa-volver' => route('solicitudes', $pedido->id_servicio)],
            ['nav-opcion-1' => 'Solicitudes', 'routa-opcion-1' => route('solicitudes', $pedido->id_servicio)],
            ['nav-opcion-2' => 'Detalles de Solicitud', 'routa-opcion-2' => null],
        ];

        foreach ($articulos as $key => $value) {
            $d = [
                'nombre' => $value -> nombre,
                'lotes_recibidos' => $value -> pivot -> lotes_recibidos,
            ];
            $detalles [] = $d;
        }
        
        return view('components.detalles-pedido', compact('rdo', 'detalles', 'breadcrumbs'));
    }

    public function detallesSolicitud($idServicio, $idPedido){
        $pedido = Pedidos::find($idPedido);
        $numero_productos = $pedido->articulos->count();
        $usuario = $pedido->usuario->nombre." ".$pedido->usuario->apellido1;
        $fecha_inicial = $pedido->fecha_inicial;
        $fecha_aceptada = $pedido->fecha_aceptada;

        $rdo = [
            'id_pedido' => $pedido->id_pedido,
            'nombre_jefe' => $usuario,
            'numero_productos' => $numero_productos,
            'fecha_inicial' => $fecha_inicial,
            'fecha_aceptada' => $fecha_aceptada,
        ];
        
        $articulos = $pedido -> articulos;
        
        $detalles = [];

        $breadcrumbs = [
            ['volver' => 'Volver', 'routa-volver' => route('solicitudes', $pedido->id_servicio)],
            ['nav-opcion-1' => 'Solicitudes', 'routa-opcion-1' => route('solicitudes', $pedido->id_servicio)],
            ['nav-opcion-2' => 'Detalles de Solicitud', 'routa-opcion-2' => null],
        ];

        foreach ($articulos as $key => $value) {
            $d = [
                'nombre' => $value -> nombre,
                'lotes_recibidos' => $value -> pivot -> lotes_recibidos,
            ];
            $detalles [] = $d;
        }
        
        return view('components.detalles-solicitud', compact('rdo', 'detalles', 'breadcrumbs'));
    }

    public function inventario($idServicio){
	
        $inventario = InventarioClinica::all();
        $articulosEnDepartamento = [];
        
        foreach($inventario as $key => $value){
            
            $pedidos = $value -> articulo -> pedidos -> where('es_departamento', true) -> where('estado', "Aceptada") -> where('id_servicio', $idServicio);
            $fechas = [];
        
            foreach ($pedidos as $key2 => $value2) {
            $fechas [] = $value2 -> fecha_aceptada;
            }

            if ($value -> inventarioDepartamentos ->where("id_servicio", $idServicio) -> first() != null) {
                $p = [
                    "id_articulo" => $value -> id_articulo_clinica,
                    "nombre" => $value -> articulo -> nombre,
                    "categoria" => $value -> articulo -> categoria -> nombre_categoria,
                    "lotes_disponibles" => $value -> inventarioDepartamentos ->where("id_servicio", $idServicio) -> first() -> pivot -> lotes_disponibles,
                    "estado" => $value -> inventarioDepartamentos ->where("id_servicio", $idServicio) -> first() -> pivot -> estado,
                    'ultima_fecha_recibida' => $fechas[array_key_last($fechas)],
                ];
            }
            
            $articulosEnDepartamento [] = $p;
        }
    
        return view('inventario', compact('articulosEnDepartamento'));
    }

    public function detalleArticulo($idServicio, $idArticulo){
     $articulo = InventarioClinica::find($idArticulo)->inventarioDepartamentos->where("id_servicio", $idServicio)->first();
      $nombreArticulo= InventarioClinica::find($idArticulo)->articulo->nombre;
      
      $pedidos=InventarioClinica::find($idArticulo)->articulo->pedidos->where("es_departamento", true)->where("estado", "Aceptada")->where("id_servicio", $idServicio);
             $pedidosProcesados = [];
            foreach ($pedidos as $key => $value) {
                    $x = [
                        "id_pedido" => $value -> id_pedido,
                        "lotes_recibidos" => $value -> pivot -> lotes_recibidos,
                    ];
                $pedidosProcesados [] = $x;
            }
      
      
        return view('components.detalles-inventario', compact('articulo','nombreArticulo','pedidosProcesados'));
    }

    public function cambiarMinimos($idServicio, $idArticulo,Request $request){
		$entrada = $request->minimo;

		InventarioClinica::find($idArticulo)->inventarioDepartamentos()->updateExistingPivot($idServicio, ["stock_minimo"=>$entrada]);
		
		$lotes_disponibles = InventarioClinica::find($idArticulo)->inventarioDepartamentos->where("id_servicio", $idServicio)->first()->pivot->lotes_disponibles;
		$stock_minimo = InventarioClinica::find($idArticulo)->inventarioDepartamentos->where("id_servicio", $idServicio)->first()->pivot->stock_minimo;
		
		if ($lotes_disponibles > $stock_minimo) {
            InventarioClinica::find($idArticulo)->inventarioDepartamentos()->updateExistingPivot($idServicio, ["estado"=>"En Stock"]);
        } else {
            InventarioClinica::find($idArticulo)->inventarioDepartamentos()->updateExistingPivot($idServicio, ["estado"=>"En Minimos"]);
        }
		return redirect()->route('inventario.detalles', [$idServicio, $idArticulo]);
    }

    public function funcionAutomatica($idServicio, $idArticulo, Request $request){

        $articulo = InventarioClinica::find($idArticulo)->articulo;
        $texto = $request->automatico;

        if ($texto == "1") {
            if ($articulo -> articuloConPedidosAutomaticos->where('id_usuario', $request->id_usuario)->count() == 0) {
                $articulo -> articuloConPedidosAutomaticos() -> attach($idArticulo, ["id_usuario"=>$request->id_usuario, "id_proveedor"=>null, "stock_a_pedir"=>$request->cantidad]);
                $texto = "creado";
                InventarioClinica::find($idArticulo)->inventarioDepartamentos()->updateExistingPivot($idServicio, ["pedido_automatico"=>true]);
            } else {
                $articulo -> articuloConPedidosAutomaticos() -> updateExistingPivot($request->id_usuario, ["id_proveedor"=>$request->null, "stock_a_pedir"=>$request->cantidad]);
                $texto = "actualizado";
                InventarioClinica::find($idArticulo)->inventarioDepartamentos()->updateExistingPivot($idServicio, ["pedido_automatico"=>true]);
            }
        } else {
                $articulo = InventarioClinica::find($idArticulo)->articulo;
              $articulo -> articuloConPedidosAutomaticos() -> detach($request->id_usuario);
              InventarioClinica::find($idArticulo)->inventarioDepartamentos()->updateExistingPivot($idServicio, ["pedido_automatico"=>false]);
        }
        return redirect()->route('inventario.detalles', [$idServicio, $idArticulo]);
    }

    public function subirPedido($idUsuario,Request $request){
        $a = $request->all();

        $rdo = [];

        foreach ($a as $key => $value) {

            $id = intval(str_replace('_', '', $key));
            $cantidad = intval($value);

            if ($id != 0 && $cantidad != 0) {
                $p = [
                    "id_articulo" => $id,
                    "cantidad" => $cantidad,
                ];

                $rdo [] = $p;
            }
        }
        
        $id_servicio = Usuario::find($idUsuario) -> servicio -> id_servicio;
        
            $pedido = new Pedidos();
            $pedido -> id_usuario_solicitante = $idUsuario;
            $pedido -> fecha_inicial = date("Y-m-d");
            $pedido -> estado = "Pendiente";
            $pedido -> es_departamento = true;
            $pedido -> id_servicio = $id_servicio;
            $pedido -> save();

        foreach ($rdo as $key2 => $value2) {
            $pedido -> articulos()->attach($value2["id_articulo"], ["id_proveedor" => null, "lotes_recibidos" => $value2["cantidad"]]);
        }

        return $id_servicio;
    }

    public function aceptarSolicitud($idServicio, $idSolicitud, Request $request){

        $a = $request->all();

        $rdo = [];

        foreach ($a as $key => $value) {

            $id = intval($key);
            $cantidad = intval($value);

            if ($key != "_method" && $key != "_token") {
                $p = [
                    "id_articulo" => $id,
                    "lotes_disponibles" => $cantidad,
                ];

                $rdo [] = $p;
            }
        }

        $coleccionArticulosMinimos = collect($rdo);
        
        $solicitud = Pedidos::find($idSolicitud);
        
        $solicitud -> fecha_aceptada = date("Y-m-d");
        $solicitud -> estado = "Aceptada";
        $solicitud -> save(); 
        
        $articulosDeSolicitud = $solicitud -> articulos;
         
        foreach($articulosDeSolicitud as $key => $value){
             
           $existeArticuloEnDepartamento = InventarioClinica::where('id_articulo', $value->id_articulo)->first()->inventarioMedicos->where("id_usuario_medico", $solicitud->id_usuario_solicitante)->count();

           $artDepSeleccionado = InventarioClinica::where('id_articulo', $value->id_articulo)->first()->inventarioDepartamentos->where("id_servicio", $idServicio)->first();
            
           $cantidadAQuitar=$value->pivot->lotes_recibidos; 
           $articuloEnMinimo=false; 

            if (count($rdo) >= 1) {
               foreach ($coleccionArticulosMinimos as $key2 => $value2) {
                   if ($value2["id_articulo"] == $value -> id_articulo) {
                       $cantidadAQuitar = $value2["lotes_disponibles"];
                       $articuloEnMinimo = true;
                     } 
                }
            }
          
            InventarioClinica::where('id_articulo', $value->id_articulo)->first()->inventarioDepartamentos()->updateExistingPivot($solicitud->id_servicio, ["lotes_disponibles"=>$artDepSeleccionado->pivot->lotes_disponibles - $cantidadAQuitar]);

                if($existeArticuloEnDepartamento == 0){
                    InventarioClinica::where('id_articulo', $value->id_articulo)->first()->inventarioMedicos()->attach($solicitud -> id_usuario_solicitante, ['id_articulo_departamento'=> $artDepSeleccionado -> pivot -> id_articulo_clinica, 'estado'=>"En Stock", "lotes_disponibles"=>$cantidadAQuitar, "stock_minimo" => 10, "pedido_automatico"=>false]);
                } else {
                    $artMedSeleccionado = InventarioClinica::where('id_articulo', $value->id_articulo)->first();
                    $stockEnDepartamento = $artMedSeleccionado->inventarioMedicos->where("id_usuario_medico", $solicitud->id_usuario_solicitante)->first()->pivot->lotes_disponibles;
                    $artMedSeleccionado->inventarioMedicos()->updateExistingPivot($solicitud -> id_usuario_solicitante, ['estado'=>"En Stock", "lotes_disponibles"=>$stockEnDepartamento + $cantidadAQuitar]);
                }

                if($articuloEnMinimo == false){
                       if($artDepSeleccionado ->pivot-> estado == "En Stock" && InventarioClinica::where('id_articulo', $value->id_articulo)->first()->inventarioDepartamentos->where("id_servicio", $idServicio)->first()->pivot->lotes_disponibles < $artDepSeleccionado->pivot->stock_minimo){
                               InventarioClinica::where('id_articulo', $value->id_articulo)->first()->inventarioDepartamentos()->updateExistingPivot($solicitud->id_servicio, ["estado"=>"En Minimos"]);
                                   if($artDepSeleccionado -> pivot -> pedido_automatico == true){
                                           $pedidoNuevo = new Pedidos();
                                            $pedidoNuevo -> id_usuario_solicitante = Servicio::find($idServicio)->jefe_departamento;
                                            $pedidoNuevo -> fecha_inicial = date('Y-m-d');
                                            $pedidoNuevo -> fecha_aceptada = null;
                                            $pedidoNuevo -> estado = "Pendiente";
                                            $pedidoNuevo -> es_departamento = true;
                                            $pedidoNuevo -> id_servicio = $idServicio;
                                            $cantidadAPedir = $value -> articuloConPedidosAutomaticos -> where("id_usuario", Servicio::find($idServicio)->jefe_departamento) -> first() -> pivot -> stock_a_pedir;
                                            $pedidoNuevo -> save();
                                            $pedidoNuevo -> articulos() -> attach(InventarioClinica::find($artDepSeleccionado->pivot->id_articulo_clinica)->articulo->id_articulo, ["id_proveedor" => null, "lotes_recibidos" => $cantidadAPedir]);
                                   }
                           }
                    }
            }

        return redirect()->route('solicitudes', $idServicio);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
