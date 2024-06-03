<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Pedidos;
class ProveedoresController extends Controller
{
    
    public function index(){
        $proveedores = Proveedor::all('id_proveedor', 'nif', 'nombre', 'telefono', 'email');
        return response()->json($proveedores);
    }

    public function numeroProveedores(){
        $proveedores = Proveedor::all();
        $cantidadDeProveedores = 0;

        foreach ($proveedores as $key => $value) {
            if ($value -> pedidos -> where("estado", "En Transito") -> count() > 0) {
                $cantidadDeProveedores++;
            }
        }
        return response()->json($cantidadDeProveedores);
    }

    public function modalProveedores(){
        $proveedores = Proveedor::all();
        
        $rdo = [];

        foreach ($proveedores as $key => $value) {

            if ($value -> pedidos -> where("estado", "En Transito") -> unique() -> count() > 0) {
                $p = [
                    "id_proveedor" => $value -> id_proveedor,
                    "nombre" => $value -> nombre,
                    "cantidad" => $value -> pedidos -> where("estado", "En Transito") -> unique() -> count(),
                ];
                $rdo [] = $p;
            }
        }

        return response()->json($rdo);
    }

    public function pedidosPendientesPorProveedor($idProveedor){
        $pedidos = Proveedor::find($idProveedor) -> pedidos -> where("estado", "En Transito") -> unique();
        $pedidosResultantes = [];
        $numero_productos = null;

        foreach ($pedidos as $key => $value) {
            $proveedor = Proveedor::find($idProveedor);
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

    public function pedidosRecibidosPorProveedor($idProveedor){
        $pedidos = Proveedor::find($idProveedor) -> pedidos -> where("estado", "Recibido") -> unique();
        $pedidosResultantes = [];
        $numero_productos = null;
        $numero_productos = null;

        foreach ($pedidos as $key => $value) {
                $nombre_proveedor = $value[0];
                $numero_productos = $value->articulos->count(); 
                $proveedor = Proveedor::find($idProveedor);
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

    public function registrarProveedor(Request $request){
        $datos = $request->all();
        $proveedor = new Proveedor();

        $proveedor -> nif = $request -> nif;
        $proveedor -> nombre = $request -> nombre;
        $proveedor -> telefono = $request -> telefono;
        $proveedor -> email = $request -> email;
        $proveedor -> codigo_postal = $request -> codigo_postal;
        $proveedor -> direccion = $request -> direccion;
        $proveedor -> provincia = $request -> provincia;
        $proveedor -> ciudad = $request -> ciudad;
        $proveedor -> cod_trasmision = $request -> cod_trasmision;

        $proveedor -> save();
    }

    public function modificarProveedor(Request $request, $idProveedor){
        $datos = $request->all();
        
        $proveedor = Proveedor::find($idProveedor);

        $proveedor -> nif = $request -> nif;
        $proveedor -> nombre = $request -> nombre;
        $proveedor -> telefono = $request -> telefono;
        $proveedor -> email = $request -> email;
        $proveedor -> codigo_postal = $request -> codigo_postal;
        $proveedor -> direccion = $request -> direccion;
        $proveedor -> provincia = $request -> provincia;
        $proveedor -> ciudad = $request -> ciudad;
        $proveedor -> cod_trasmision = $request -> cod_trasmision;

        $proveedor -> save();
    }
}
