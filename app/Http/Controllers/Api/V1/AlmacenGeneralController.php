<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlmacenGeneral;
use App\Models\Proveedor;
use App\Models\InventarioClinica;

class AlmacenGeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function articulosCrearPedido()
    {

        $articulos = AlmacenGeneral::all();
        $rdo = [];
            foreach ($articulos as $key => $value) {
                $estado = "N/A";
                $nLotes = 0;

                if (InventarioClinica::where("id_articulo", $value->id_articulo)->first() != null) {
                    $nLotes = InventarioClinica::where("id_articulo", $value->id_articulo)->first() -> lotes_disponibles;
                    $estado = InventarioClinica::where("id_articulo", $value->id_articulo)->first() -> estado;
                }

                $p = [
                    "id_articulo" => $value->id_articulo,
                    "nombre" => $value->nombre,
                    "nombre_categoria" => $value->categoria->nombre_categoria,
                    "nLotes" => $nLotes,
                    "nombre_proveedor" => $estado, // Se reutiliza la interfaz para poder almacenar el estado y con eso marcar el item en el listado
                ];

                $rdo [] = $p;
            }

        return response()->json($rdo);
    }

    public function articulosMinimosCrearPedidoGestor(){
        $articulos = AlmacenGeneral::all();

        $arts = [];

        foreach ($articulos as $key => $value) {

            if (InventarioClinica::where("id_articulo", $value->id_articulo)->first() != null) {
                if (InventarioClinica::where("id_articulo", $value->id_articulo)-> first() -> estado == "En Minimos") {
                    $nLotes = InventarioClinica::where("id_articulo", $value->id_articulo)->first() -> lotes_disponibles;
                    $estado = InventarioClinica::where("id_articulo", $value->id_articulo)->first() -> estado;
                    $p = [
                        "id_articulo" => $value->id_articulo,
                        "nombre" => $value->nombre,
                        "nombre_categoria" => $value->categoria->nombre_categoria,
                        "nLotes" => $nLotes,
                        "nombre_proveedor" => $estado, // Se reutiliza la interfaz para poder almacenar el estado y con eso marcar el item en el listado
                    ];
                    $arts []= $p;
                }
            }
        }
        return response()->json($arts);
    }


    public function articulosCrearPedidoMedico($idMedico){
        $articulos = InventarioClinica::all();

        $arts = [];

        foreach ($articulos as $key => $value) {

            if ($value->inventarioDepartamentos->where("id_servicio", 6)->first() != null) {

                $nLotes = 0;
                $estado = "";
                if ($value -> inventarioMedicos -> where("id_servicio", 6) -> first() != null) {
                    $nLotes = $value -> inventarioMedicos -> where("id_usuario_medico", $idMedico) -> first() -> pivot -> lotes_disponibles;
                    $estado = $value -> inventarioMedicos -> where("id_usuario_medico", $idMedico) -> first() -> pivot -> estado;
                }

                $p [] = [
                    "id_articulo" => $value->inventarioDepartamentos->where("id_servicio", 6)->first()->pivot->id_articulo_clinica,
                    "nombre" => AlmacenGeneral::find($value->id_articulo)->nombre,
                    "nombre_categoria" => AlmacenGeneral::find($value->id_articulo)->categoria->nombre_categoria,
                    "nLotes" => $nLotes,
                    "nombre_proveedor" => $estado, // Se reutiliza la interfaz para poder almacenar el estado y con eso marcar el item en el listado
                ];
                
                $arts = $p;
            }
        }
        return response()->json($arts);
    }
    
    public function articulosMinimosCrearPedidoMedico($idMedico){
        $articulos = InventarioClinica::all();

        $arts = [];

        foreach ($articulos as $key => $value) {

            if ($value->inventarioDepartamentos->where("id_servicio", 6)->first() != null && $value -> inventarioMedicos -> where("id_usuario_medico", $idMedico) -> first() != null) {
							
			    if($value -> inventarioMedicos -> where("id_usuario_medico", $idMedico) -> first() -> pivot -> estado == "En Minimos"){
							
		                $nLotes = 0;
		                if ($value -> inventarioMedicos -> where("id_servicio", 6) -> first() != null) {
		                    $nLotes = $value -> inventarioMedicos -> where("id_usuario_medico", $idMedico) -> first() -> pivot -> lotes_disponibles;
		                }
		
		                $p [] = [
		                    "id_articulo" => $value->inventarioDepartamentos->where("id_servicio", 6)->first()->pivot->id_articulo_clinica,
		                    "nombre" => AlmacenGeneral::find($value->id_articulo)->nombre,
		                    "nombre_categoria" => AlmacenGeneral::find($value->id_articulo)->categoria ->nombre_categoria,
		                    "nLotes" => $nLotes,
		                    "nombre_proveedor" => $value -> inventarioMedicos -> where("id_usuario_medico", $idMedico) -> first() -> pivot -> estado, // Se reutiliza la interfaz para poder almacenar el estado y con eso marcar el item en el listado
		                ];
		                
		                $arts = $p;
		                
		           }   
            }
        }
        return response()->json($arts);
    }

    public function numeroLotesCrearPedidoMedico($idMedico, $idArticulo){
        if (InventarioClinica::find($idArticulo) -> inventarioMedicos ->  where("id_usuario_medico", $idMedico) -> first() != null) {
            $nLotes = InventarioClinica::find($idArticulo) -> inventarioMedicos ->  where("id_usuario_medico", $idMedico) -> first() -> pivot  -> lotes_disponibles;
            return response()->json($nLotes);
        }
        return response()->json(0);
    }

    public function numeroLotesCrearPedidoGestor($idArticulo){
        if (InventarioClinica::where("id_articulo", $idArticulo) -> first() != null) {
            $nLotes = InventarioClinica::where("id_articulo", $idArticulo) -> first() -> lotes_disponibles;
            return response()->json($nLotes);
        }
        return response()->json(0);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function detallesArticulosCrearPedido($id)
    {
        $articulo = AlmacenGeneral::find($id);

        $proveedores = $articulo -> proveedores;
        
        foreach ($proveedores as $key => $value) {
            $paquete = [
                'id_proveedor' => $value -> id_proveedor,
                'nombre_proveedor' => $value -> nombre,
            ];
            $datosProveedores [] = $paquete;
        }

        return response()->json($datosProveedores);
    }

    public function proveedoresSegunArticulo($idArticulo){
        $proveedor = AlmacenGeneral::find($idArticulo) -> proveedores;
        $datosProveedores = [];
        foreach ($proveedor as $key => $value) {
            $paquete = [
                'id_proveedor' => $value -> id_proveedor,
                'nombre_proveedor' => $value -> nombre,
            ];

            $datosProveedores [] = $paquete;
        }
        return response()->json($datosProveedores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function detallesArticulosSegunProveedor($idArticulo, $idProveedor)
    {
        $proveedor = Proveedor::find($idProveedor) -> articulos -> where('id_articulo', $idArticulo) -> first();

        return response()->json([$proveedor -> pivot -> cantidad_por_lote, $proveedor -> pivot -> coste_por_lote]);
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
