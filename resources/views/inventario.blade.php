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

            <div class="col-8 d-flex mb-5 justify-content-between">
                    <div class="card shadow-sm border borderless" style="background-color: #EBF3FA; width: 30rem;" onclick="abrirModalMinimos()">
                        <div class="card-body mt-3 ms-2">
                            <div class="d-flex mb-3">
                                <i class="fa-solid fa-triangle-exclamation fa-2x mt-1"></i>
                                <h1 class="font fs-2 ms-3">{{count($articulosMinimos)}}</h1>
                            </div>
                            <h5 style="font-family: manrope; font-weight:900;" class="ms-1">Articulos por Debajo del Stock Minimo</h5>
                        </div>
                    </div>

                    <div class="card shadow-sm border borderless" style="background-color: #EBF3FA; width: 30rem;" onclick="abrirModalAutomaticos()">
                        <div class="card-body mt-3 ms-2">
                            <div class="d-flex mb-3">
                                <i class="fa-solid fa-gears fa-2x mt-1"></i>
                                </fa-icon><h1 class="font fs-2 ms-3">{{count($articulosAutomaticos)}}</h1>
                            </div>
                            <h5 style="font-family: manrope; font-weight:900;" class="ms-1">Articulos con Pedido Automatico</h5>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-2">
                <div class="col-12 px-5">
                <table class="table table-hover" id="inventario-table">
                        <thead>
                            <tr>    
                                    <td>NºArticulo</td>
                                    <td>Nombre</td>
                                    <td>Categoria</td>
                                    <td>Lotes Disponibles</td>
                                    <td>Estado</td>
                                    <td>Ultima Vez Pedido</td>
                                    <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articulosEnDepartamento as $articulo)
                            <tr>
                                <td>{{$articulo['id_articulo']}}</td>
                                <td>{{$articulo['nombre']}}</td>
                                <td>{{$articulo['categoria']}}</td>
                                <td>{{$articulo['lotes_disponibles']}}</td>
                                <td>{{$articulo['estado']}}</td>
                                <td>{{$articulo['ultima_fecha_recibida']}}</td>
                                <td><a href="{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$articulo['id_articulo']}}"><i class="fa-solid fa-eye fa-1x"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="articulosMinimos" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body p-5">
            <p class="font fs-4">Articulos con Stocks Minimos</p>
            <div class="row py-5">
                <div class="col-12">
                    <table class="table table-hover" id="inventario-minimo-table">
                        <thead>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody style="background-color: white;">
                        @foreach($articulosMinimos as $articuloMinimo)
                            <tr>
                                <td>{{$articuloMinimo["nombre"]}}</td>
                                <td>{{$articuloMinimo["categoria"]}}</td>
                                <td><a href="{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$articuloMinimo['id_articulo']}}"><i class="fa-solid fa-eye fa-1x"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-cancelar me-5" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>  
            </div>
      </div>
    </div>
</div>

<div class="modal fade" id="articulosAutomaticos" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body p-5">
            <p class="font fs-4">Articulos con Stocks Minimos</p>
            <div class="row py-5">
                <div class="col-12">
                    <table class="table table-hover" id="inventario-automatico-table">
                        <thead>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody style="background-color: white;">
                        @foreach($articulosAutomaticos as $articuloAutomatico)
                            <tr>
                                <td>{{$articuloAutomatico["nombre"]}}</td>
                                <td>{{$articuloAutomatico["categoria"]}}</td>
                                <td><a href="{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$articuloAutomatico['id_articulo']}}"><i class="fa-solid fa-eye fa-1x"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-cancelar me-5" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>  
            </div>
      </div>
    </div>
</div>

<style>
    .card:hover{
        cursor: pointer;
    }
    #inventario-minimo-table_wrapper{
        width: 450px;
    }
    #inventario-automatico-table_wrapper{
        width: 450px;
    }
</style>

<script type="text/javascript">
    var dtOptions = {
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                emptyTable: ''
            },
            pagingType: "numbers",
            info: false,
            scrollY: '350px',
            fixedHeader: {
            header: true,
            },
        };
        tabla1 = new DataTable('#inventario-table', dtOptions);
        tabla2 = new DataTable('#inventario-minimo-table', dtOptions);
        tabla3 = new DataTable('#inventario-automatico-table', dtOptions);

        var modal1 = new bootstrap.Modal(document.getElementById('articulosMinimos'));
        var modal2 = new bootstrap.Modal(document.getElementById('articulosAutomaticos'));
        

        function abrirModalMinimos(){
            modal1.toggle();
            $('#articulosMinimos').on('shown.bs.modal', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();});
        }

        function abrirModalAutomaticos(){
            modal2.toggle();
            $('#articulosAutomaticos').on('shown.bs.modal', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();});
        }
        
</script>
@endsection