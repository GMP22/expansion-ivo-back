<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventarioClinica;
use App\Models\CategoriaArticulos;
use App\Models\Usuario;

class InventarioClinicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
                    'nombre_articulo' => $nombreArticulo,
                    'nombre_categoria' => $categoria,
                    'numero_lotes' => $lotesDisponibles,
                    'ultima_fecha_recibida' => $fechas[array_key_last($fechas)],
                ];
                $articulosResultantes[] = $a;
            }
        return response()->json($articulosResultantes);
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
