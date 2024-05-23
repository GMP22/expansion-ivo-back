@extends('layouts.plantilla')
@section('content')
<div class="row">
    <div class="col-12 font mt-5 ms-5">
        <p class="h1">Detalles de Pedido</p>
    </div>
</div>

<div class="row">
    <div class="row">
        <div class="col-4">
            <h2>Numero de Pedido</h2>
            <h3>{{$rdo['id_pedido']}}</h3>
        </div>
        <div class="col-4">
            <h2>Departamento Solicitante</h2>
            <h3>{{$rdo['nombre_departamento']}}</h3>
        </div>
        <div class="col-4">
            <h2>Nombre de Solicitante</h2>
            <h3>{{$rdo['nombre_jefe']}}</h3>
        </div>
    </div>
    
    <div class="row">
        <div class="col-4">
            <h2>Numero de Productos</h2>
            <h3>{{$rdo['numero_productos']}}</h3>
        </div>
        <div class="col-4">
            <h2>Fecha de Pedido</h2>
            <h3>{{$rdo['fecha_inicial']}}</h3>
        </div>
        @if ($rdo['fecha_aceptada'] != null)
        <div class="col-4">
            <h2>Fecha Aceptada</button></h2>
            <h3>{{$rdo['fecha_aceptada']}}</h3>
        </div>
        @endif
    </div>
</div>

<table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre Articulo</th>
                                <th>Lotes Recibidos</th>
                            </tr>
  </thead>
       <tbody>
         @foreach($detalles as $articulo)
            <tr>
                <td>{{$articulo['nombre']}}</td>
                <td>{{$articulo['lotes_recibidos']}}</td>
           </tr>
         @endforeach
    </tbody>
</table>
@endsection