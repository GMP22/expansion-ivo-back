<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventarioClinica;
use App\Models\Usuario;
use App\Models\Pedidos;
class JefeDepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function plantilla()
    {
        return view('layouts.plantilla');
    }

    /*
        $tipo_usuario = $request->input('tipo_usuario', 'Médico'); 
        $rol = Rol::where('nombre', $tipo_usuario)->first();
        $usuarios = Usuario::where('id_rol', $rol->id_rol)->get();
        return view('usuario.gestor.dashboard', compact('usuarios', 'tipo_usuario'));
    */

    public function pedidos($id_servicio)
    {   
        $pedidosPendientes = Pedidos::where("estado", "Pendiente")->where("id_servicio", $id_servicio)->get();
        $pedidosAceptados = Pedidos::where("estado", "Aceptada")->where("id_servicio", $id_servicio)->get();
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

    public function crearPedidos()
    {   
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
   
    public function inventario()
    {
        return view('inventario');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function subirPedido($idUsuario,Request $request)
    {
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
