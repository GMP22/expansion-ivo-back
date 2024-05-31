@extends('layouts.plantilla')
@section('content')
<link rel="stylesheet" href="{{ asset('css/gestor.css') }}">
<div class="row mt-4">
     <div class="col-12 p-0">
       <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </div>
</div>

<div class="row">
    <div class="col-12 font mt-5 ms-5">
        <p class="h1" style="font-weight: bold;">Detalles de Solicitud</p>
    </div>
</div>

<div class="row my-5">
    <div class="row px-5">
        <div class="col-4">
            <h4>Numero de Pedido</h4>
            <h5 class="font" style="font-weight: bold;">{{$rdo['id_pedido']}}</h5>
        </div>
        <div class="col-4">
            <h4>Nombre de Solicitante</h4>
            <h5 class="font" style="font-weight: bold;">{{$rdo['nombre_jefe']}}</h5>
        </div>
        <div class="col-4">
            <h4>Numero de Productos</h4>
            <h5 class="font">{{$rdo['numero_productos']}}</h5>
        </div>
    </div>
    
    <div class="row px-5 mt-5">
        <div class="col-4">
            <h4>Fecha de Pedido</h4>
            <h5 class="font" style="font-weight: bold;">{{$rdo['fecha_inicial']}}</h5>
        </div>
        @if ($rdo['fecha_aceptada'] != null)
        <div class="col-4">
            <h4>Fecha Aceptada</button></h4>
            <h5 class="font" style="font-weight: bold;">{{$rdo['fecha_aceptada']}}</h5>
        </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-8 px-5">
            <table datatable class="table table-hover " id="articulos">
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
    </div>
</div>



<script>
        var dtOptions = {
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                    emptyTable: ''
                },
                pagingType: "numbers",
                info: false,
                dom: 'rt',
            };

        new DataTable('#articulos', dtOptions);
</script>

@endsection