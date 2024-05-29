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

        $articulos = AlmacenGeneral::all('id_articulo', 'nombre');

        return response()->json($articulos);
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
    
 /*
	Esta es la version donde se filtra por el estado
*/   
    
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

    /**
     * Show the form for creating a new resource.
     */
    public function detallesArticulosCrearPedido($id)
    {
        $articulo = AlmacenGeneral::find($id);

        $proveedores = $articulo -> proveedores;
        $datosFinales=[];
        
        $categoria = $articulo -> categoria;
        foreach ($proveedores as $key => $value) {
            $paquete = [
                'id_proveedor' => $value -> id_proveedor,
                'nombre_proveedor' => $value -> nombre,
            ];

            $datosProveedores [] = $paquete;
        }
        $datosFinales [] = $datosProveedores;
        $datosFinales [] = $categoria -> nombre_categoria;

        return response()->json($datosFinales);
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
