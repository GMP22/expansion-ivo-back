<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PedidosPendientesResource;
use App\Models\Pedidos;
use App\Models\Usuario;
use App\Models\Proveedor;
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
    
    public function mostrarPedidosSegunId($id)
    {
        $user = Usuario::find($id);
        $pedidosDeUser = $user -> pedidos;
        return response()->json([$pedidosDeUser]);
    }

    public function pedidosPendientesGestor($id)
    {   
        $user = Usuario::find($id);
        $pedido = $user -> pedidos -> where("fecha_aceptada", null);

        $pedidosResultantes = [];
        $nombre_proveedor = null;
        $numero_productos = null;
        foreach ($pedido as $key => $value) {
            $nombre_proveedor = $value[0];
            $numero_productos = $value->articulos->count(); 
            $proveedor = Proveedor::find($value->proveedores[0]->id_proveedor);
            $x = $proveedor -> articulos;
            $articulos = [];
                
                for ($j=0; $j < $numero_productos; $j++) { 
                    $articulos [] = $x[$j]->pivot->coste_por_lote * $value->articulos[$j]->pivot->lotes_recibidos;
                }

            $p = [
                'pedido' => $value->id_pedido,
                'proveedor' => $value->proveedores[0]->nombre,
                'fecha_inicial' => $value->fecha_inicial,
                'numero_productos' => $numero_productos,
                'coste' => $articulos, 
            ];
           
            $pedidosResultantes[] = $p;
        };
        return response()->json($pedidosResultantes);
    }

    public function pedidosRecibidosGestor($id)
    {
        $pedidosDeUser = Pedidos::where([['id_usuario_solicitante', '=', $id], ['estado', '!=', 'En Transito']])->get();
        return response()->json($pedidosDeUser);
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
