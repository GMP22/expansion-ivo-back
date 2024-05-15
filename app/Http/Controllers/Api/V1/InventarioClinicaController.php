<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventarioClinica;
use App\Models\CategoriaArticulos;
use App\Models\Usuario;
use App\Models\Proveedor;
use App\Models\Pedidos;
use App\Models\AlmacenGeneral;

class InventarioClinicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    public function eliminarFuncionAutomatica(Request $request){
        $articulo = AlmacenGeneral::find($request->id_articulo);
        $articulo -> articuloConPedidosAutomaticos() -> detach($request->id_usuario);
        return response()->json("Eliminado exitosamente el pedido automatico", 200);
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
        //
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
