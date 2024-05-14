<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PedidosPendientesResource;
use App\Models\Pedidos;
use App\Models\Usuario;
use App\Models\Proveedor;
use App\Models\InventarioClinica;
use Illuminate\Http\Request;

class PedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 1;
    }
    
    public function mostrarPedidosSegunId($id){
        $user = Usuario::find($id);
        $pedidosDeUser = $user -> pedidos;
        return response()->json($pedidosDeUser);
    }

    public function pedidosPendientesGestor($id){   
        $user = Usuario::find($id);
        $pedido = $user -> pedidos -> where("fecha_aceptada", null);

        $pedidosResultantes = [];
        $numero_productos = null;
        foreach ($pedido as $key => $value) {
            
            $proveedor = Proveedor::find($value->proveedores[0]->id_proveedor);
            $articulos = $value->articulos;
            $costeArticulos = 0;

                foreach ($articulos as $key2 => $value2) {
                   $costeArticulos += $value2->pivot->lotes_recibidos * $value2->proveedores[0]->pivot->coste_por_lote;
                }
            $numero_productos = $value->articulos->count(); 

            $p = [
                'id_pedido' => $value->id_pedido,
                'proveedor' => $value->proveedores[0]->nombre,
                'fecha_inicial' => $value->fecha_inicial,
                'numero_productos' => $numero_productos,
                'coste' => $costeArticulos, 
            ];
           
            $pedidosResultantes[] = $p;
            
        };
        return response()->json($pedidosResultantes);
    }

    public function detallesPedido($id){
        $pedido = Pedidos::find($id);
        $articulos = $pedido -> articulos;
        $detalle = [];

        foreach ($articulos as $key => $value) {
            $costePorLote = $value -> proveedores -> where('id_proveedor', $value->pivot->id_proveedor) -> first() -> pivot -> coste_por_lote;

            $d = [
                'nombre' => $value -> nombre,
                'lotes_recibidos' => $value -> pivot -> lotes_recibidos,
                'coste_por_lote' => $costePorLote,
                'precio' => $value -> pivot -> lotes_recibidos*$costePorLote
            ];

            $detalle [] = $d;
        }
         
        return response()->json($detalle);
    }

    public function pedidosRecibidosGestor($id){
         
            $user = Usuario::find($id);
            $pedido = $user -> pedidos -> where("estado", "Recibido");
    
            $pedidosResultantes = [];
            $nombre_proveedor = null;
            $numero_productos = null;
            foreach ($pedido as $key => $value) {
                $nombre_proveedor = $value[0];
                $numero_productos = $value->articulos->count(); 
                $proveedor = Proveedor::find($value->proveedores[0]->id_proveedor);
                $x = $proveedor -> articulos;
                $costeArticulos = 0;
                    
                    for ($j=0; $j < $numero_productos; $j++) { 
                        $costeArticulos += $x[$j]->pivot->coste_por_lote * $value->articulos[$j]->pivot->lotes_recibidos;
                    }
    
                $p = [
                    'id_pedido' => $value->id_pedido,
                    'proveedor' => $value->proveedores[0]->nombre,
                    'fecha_inicial' => $value->fecha_inicial,
                    'fecha_aceptada' => $value->fecha_aceptada,
                    'numero_productos' => $numero_productos,
                    'coste' => $costeArticulos, 
                ];
               
                $pedidosResultantes[] = $p;
            };
            return response()->json($pedidosResultantes);
        
    }

    public function solicitudesEntrantesGestor($id){
        $pedidos = Pedidos::where([['es_departamento', '=', true], ['estado', '=', "Pendiente"]])->get();
        $pedidosResultantes = [];
        foreach ($pedidos as $key => $value) {
            $departamento = $value->servicio;
            $articulos = $value->articulos->count();
            $p = [
                'solicitud' => $value->id_pedido,
                'nombre_departamento' => $departamento->nombre_servicio,
                'numero_productos' => $articulos,
                'fecha_inicial' => $value->fecha_inicial,
            ];
            $pedidosResultantes[] = $p;
        }
        return response()->json($pedidosResultantes);
    }

    public function solicitudesAceptadasGestor($id){
        $pedidos = Pedidos::where([['es_departamento', '=', true], ['estado', '=', "Aceptado"]])->get();
        $pedidosResultantes = [];
        foreach ($pedidos as $key => $value) {
            $departamento = $value->servicio;
            $articulos = $value->articulos->count();
            $p = [
                'solicitud' => $value->id_pedido,
                'id_servicio' => $departamento->id_servicio,
                'nombre_departamento' => $departamento->nombre_servicio,
                'numero_productos' => $articulos,
                'fecha_inicial' => $value->fecha_inicial,
                'fecha_aceptada' => $value->fecha_aceptada,
            ];
            $pedidosResultantes[] = $p;
        }
        return response()->json($pedidosResultantes);
    }

    public function registrarPedidosGestor($idUsuario, Request $request){
        $articulosAPedir = $request->all();
        $rdo = usort($articulosAPedir, function($a, $b) {
            if ($a["id_proveedor"] == $b["id_proveedor"]) {
                return 0;
            }
            return ($a["id_proveedor"] < $b["id_proveedor"]) ? -1 : 1;
        });

        $coleccion = collect($articulosAPedir);
        $nProveedores = $coleccion->unique('id_proveedor')->values()->all();

        $xd = "Pedidos Procesados exitosamente";

        foreach ($nProveedores as $key => $value) {

            $pedido = new Pedidos();
            $pedido -> id_usuario_solicitante = $idUsuario;
            $pedido -> fecha_inicial = date("Y-m-d");
            $pedido -> estado = "En Transito";
            $pedido -> es_departamento = false;
            $pedido -> id_servicio = null;
            $pedido -> save();
            //$user->roles()->attach($roleId, ['expires' => $expires]);
            foreach ($coleccion as $key2 => $value2) {
                if($value["id_proveedor"] == $value2["id_proveedor"]){
                    $pedido -> articulos() -> attach($value2["id_articulo"], ["id_proveedor" => intval($value2["id_proveedor"]), "lotes_recibidos" => $value2["nLotes"], "fecha_aceptada" => null]);
                }
            }
        }
        return response()->json($xd, 200);
    }

    public function recibirPedidoGestor($idArticulo){
        $pedido = Pedidos::find($idArticulo);
        
        $pedido -> fecha_aceptada = date("Y-m-d");
        $pedido -> estado = "Recibido";
        $pedido -> save();

        $articulos = $pedido -> articulos;

        foreach ($articulos as $key => $value) {
            $articuloClinica = InventarioClinica::where('id_articulo', $value->id_articulo) -> get();

            if ($articuloClinica -> count() == 1) {
                $articuloClinica[0] -> lotes_disponibles = $articuloClinica[0] -> lotes_disponibles + $value -> pivot -> lotes_recibidos;
                $articuloClinica[0] -> save();
            } else {
                $nuevoArticuloClinica = new InventarioClinica();
                $nuevoArticuloClinica -> id_articulo = $value -> id_articulo;
                $nuevoArticuloClinica -> estado = "En Stock";
                $nuevoArticuloClinica -> lotes_disponibles = $value -> pivot -> lotes_recibidos;
                $nuevoArticuloClinica -> stock_minimo = 10;
                $nuevoArticuloClinica -> pedido_automatico = false;
                $nuevoArticuloClinica -> save();
            }
        }

        return response()->json("Pedido exitosamente recibido", 200); 
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
