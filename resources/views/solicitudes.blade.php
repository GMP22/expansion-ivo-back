@extends('layouts.plantilla')

@section('content')


    <div class="row">
        <div class="col-12 px-5">
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
                </div>
            </div>

            @isset($minimos2)
            <p>hola</p>
                {{$cantidad}}
            @endisset

            <div class="row">
                <div class="col-12 px-5">
                    <div id="pendiente">
                        <table class="table table-hover" id="pedidos-pendientes-table">
                            <thead>
                                <tr>
                                    <th>NºPedido</th>
                                    <th>Nombre de Solicitante</th>
                                    <th>Numero de Productos</th>
                                    <th>Fecha Inicial</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rdo as $pedido)
                                <tr>
                                    <td>{{$pedido['id_pedido']}}</td>
                                    <td>{{$pedido['nombre_usuario']}}</td>
                                    <td>{{$pedido['numero_productos']}}</td>
                                    <td>{{$pedido['fecha_inicial']}}</td>
                                    <td><button type="button"><a href="/pedidos/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_pedido']}}">Ver Detalles</a></button> <button type="button"><a href="/solicitudes/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_pedido']}}/comprobar-minimos">Aceptar Solicitud</a></button></td>
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
                                    <th>Nombre de Solicitante</th>
                                    <th>Numero de Productos</th>
                                    <th>Fecha Inicial</th>
                                    <th>Fecha Aceptada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rdo2 as $pedido)
                                <tr>
                                    <td>{{$pedido['id_pedido']}}</td>
                                    <td>{{$pedido['nombre_usuario']}}</td>
                                    <td>{{$pedido['numero_productos']}}</td>
                                    <td>{{$pedido['fecha_inicial']}}</td>
                                    <td>{{$pedido['fecha_aceptada']}}</td>
                                    <td><button class="meter" type="button"><a href="/pedidos/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_pedido']}}">Ver Detalles</a></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



<div class="modal fade" id="cambiarMinimos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        </div>
            <div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Cambiar Minimo</button>
            </div>  
      </div>
    </div>
</div>
            

    <script type="text/javascript">

    /*
        var modal1 = new bootstrap.Modal(document.getElementById('cambiarMinimos'));
        modal1.toggle();
    */

    var dtOptions = {
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                emptyTable: ''
            },
            pagingType: "numbers",
            info: false
        };
        new DataTable('#pedidos-pendientes-table', dtOptions);
        new DataTable('#pedidos-aceptados-table', dtOptions);
        $('#realizada').hide();
       

        $("#historial").on("click", function(){
            $("#realizada").show();
            $('#historial').removeClass("disabled").addClass("enabled");
            $('#entradas').removeClass("enabled").addClass("disabled");
            $('#pedidos-pendientes-table_wrapper').hide();
            $('#pedidos-aceptados-table_wrapper').show();
        })

        $("#entradas").on("click", function(){
            $('#historial').removeClass("enabled").addClass("disabled");
            $('#entradas').removeClass("disabled").addClass("enabled");
            $('#pedidos-aceptados-table_wrapper').hide();
            $('#pedidos-pendientes-table_wrapper').show();
        })
     
</script>
@endsection