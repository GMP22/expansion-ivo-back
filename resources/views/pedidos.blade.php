@extends('layouts.plantilla')

@section('content')

    <div class="row mt-3">
        <div class="col-12 px-5">

            <div class="row py-5">
                <div class="col-12">
                    <h1 class="title color">Pedidos</h1>
                </div>
            </div>

            <div class="row">

                <div class="col-10 d-flex mb-5 justify-content-between">
                    <div class="card shadow-sm border borderless" style="background-color: #EBF3FA; width: 17rem;">
                        <div class="card-body mt-3 ms-2">
                            <div class="d-flex mb-3">
                                <i class="fa-solid fa-truck-fast fa-2x mt-1"></i>
                                <h1 class="font fs-2 ms-3">{{$numeroPendientes}}</h1>
                            </div>
                            <h5 style="font-family: manrope; font-weight:900;" class="ms-2">Pedidos Pendientes</h5>
                        </div>
                    </div>

                    <div  class="card shadow-sm border borderless" style="background-color: #EBF3FA; width: 17rem;">
                        <div class="card-body mt-3 ms-2">
                            <div class="d-flex mb-3">
                                <i class="fa-solid fa-boxes-stacked fa-2x mt-1"></i>
                                </fa-icon><h1 class="font fs-2 ms-3">{{$numeroAceptados}}</h1>
                            </div>
                            <h5 style="font-family: manrope; font-weight:900;" class="ms-1">Pedidos Recibidos</h5>
                        </div>
                    </div>

                    <div  class="card shadow-sm border borderless" style="background-color: #EBF3FA; width: 17rem;">
                        <div class="card-body mt-3 ms-2">
                            <div class="d-flex mb-3">
                                <i class="fa-solid fa-triangle-exclamation fa-2x mt-1"></i>
                                </fa-icon><h1 class="font fs-2 ms-3">{{$numeroDeInventario}}</h1>
                            </div>
                            <h5 style="font-family: manrope; font-weight:900;" class="ms-1">Articulos Minimos</h5>
                        </div>
                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-6 mb-5 ms-3"> <!--Este apartado despliega los componentes que correspondan a una ruta especifica-->
                  
                    <div class="row font">
                        <div class="col-2 text-center enabled" id="entradas">
                                <span >Entradas</span>
                            </div>
                            <div class="col-2 text-center disabled" id="historial">
                                <span >Historial</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 d-flex flex-row flex-row-reverse h-50">
                       <a class="btn-cancelar font me-3" href="/pedidos/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/crear-pedido">Crear Pedido</a>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-10 ps-5">

                    <div id="pendiente">
                    <table class="table table-hover" id="pedidos-pendientes-table">
                        <thead>
                            <tr>
                                <th>NºPedido</th>
                                <th>Numero de Productos</th>
                                <th>Fecha Inicial</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rdo1 as $pedido)
                            <tr>
                                <td>{{$pedido['id_pedido']}}</td>
                                <td>{{$pedido['numero_productos']}}</td>
                                <td>{{$pedido['fecha_inicial']}}</td>
                                <td><a href="/pedidos/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_pedido']}}"><i class="fa-solid fa-eye"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    

                    <div id="realizada">
                        <table class="table table-hover" id="pedidos-aceptados-table">
                            <thead>
                                <tr>
                                    <th>NºPedido</th>
                                    <th>Numero de Productos</th>
                                    <th>Fecha Inicial</th>
                                    <th>Fecha Aceptada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rdo2 as $pedidos)
                                <tr>
                                    <td>{{$pedidos['id_pedidos']}}</td>
                                    <td>{{$pedidos['numero_productos']}}</td>
                                    <td>{{$pedidos['fecha_inicial']}}</td>
                                    <td>{{$pedidos['fecha_aceptada']}}</td>
                                    <td><a href="/pedidos/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_pedido']}}"><i class="fa-solid fa-eye"></i></a></td>
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
            scrollY: '300px',
            info: false,
        };
       var tabla1 = new DataTable('#pedidos-pendientes-table', dtOptions);
       var tabla2 = new DataTable('#pedidos-aceptados-table', dtOptions);
        
        $('#realizada').hide();
       

        $("#historial").on("click", function(){
            $("#realizada").show();
            $('#historial').removeClass("disabled").addClass("enabled");
            $('#entradas').removeClass("enabled").addClass("disabled");
            $('#pedidos-pendientes-table_wrapper').hide();
            $('#pedidos-aceptados-table_wrapper').show();
            tabla2.columns.adjust().draw();
            
        })

        $("#entradas").on("click", function(){
            $('#historial').removeClass("enabled").addClass("disabled");
            $('#entradas').removeClass("disabled").addClass("enabled");
            $('#pedidos-aceptados-table_wrapper').hide();
            $('#pedidos-pendientes-table_wrapper').show();
            tabla1.columns.adjust().draw();
        })
     
</script>

@endsection