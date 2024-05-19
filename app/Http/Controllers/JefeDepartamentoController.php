<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JefeDepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function plantilla()
    {
        return view('layouts.plantilla');
    }

    public function pedidos()
    {
        return view('pedidos');
    }
   
    public function inventario()
    {
        return view('inventario');
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
