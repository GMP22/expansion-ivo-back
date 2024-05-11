<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlmacenGeneral;
use App\Models\Proveedor;
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
