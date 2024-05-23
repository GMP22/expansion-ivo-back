@extends('layouts.plantilla')

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-12 px-5">

            <div class="row py-5">
                <div class="col-12">
                    <h1 class="title color">Inventario</h1>
                </div>
            </div>

            <div class="row">

                <div class="col-7 d-flex mb-5 justify-content-between">
                    <div class="card" style="width: 15rem;">
                        <div class="card-body mt-3">
                            <div class="d-flex">
                                <fa-icon [icon]="faMagnifyingGlass" class="mx-2 icon-calender">
                                </fa-icon><h2>asdas</h2>
                            </div>
                            <h2 class="card-title">Card title</h2>
                        </div>
                    </div>

                    <div class="card" style="width: 15rem;">
                        <div class="card-body mt-3">
                            <div class="d-flex">
                                <fa-icon [icon]="faMagnifyingGlass" class="mx-2 icon-calender">
                                </fa-icon><h2>asdas</h2>
                            </div>
                            <h2 class="card-title">Card title</h2>
                        </div>
                    </div>

                    <div class="card" style="width: 15rem;">
                        <div class="card-body mt-3">
                            <div class="d-flex">
                                <fa-icon [icon]="faMagnifyingGlass" class="mx-2 icon-calender">
                                </fa-icon><h2>asdas</h2>
                            </div>
                            <h2 class="card-title">Card title</h2>
                        </div>
                    </div>

                </div>

            </div>

            <div class="row mt-2">
                <div class="col-12 px-5">
                <table class="table table-hover" id="pedidos-pendientes-table">
                        <thead>
                            <tr>
                                    <td>nombre</td>
                                    <td>categoria</td>
                                    <td>lotes_disponibles</td>
                                    <td>estado</td>
                                    <td>ultima_fecha_recibida</td>
                                    <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articulosEnDepartamento as $articulo)
                            <tr>
                                <td>{{$articulo['nombre']}}</td>
                                <td>{{$articulo['categoria']}}</td>
                                <td>{{$articulo['lotes_disponibles']}}</td>
                                <td>{{$articulo['estado']}}</td>
                                <td>{{$articulo['ultima_fecha_recibida']}}</td>
                                <td><button type="button"><a href="/inventario/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$articulo['id_articulo']}}">Añadir</a></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var dtOptions = {
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                emptyTable: ''
            },
            pagingType: "numbers",
            info: false
        };
        new DataTable('#usuarios-table', dtOptions);
</script>
@endsection