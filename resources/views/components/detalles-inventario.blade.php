@extends('layouts.plantilla')
@section('content')

<div class="row mt-4">
     <div class="col-12 p-0">
       <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </div>
</div>

<div class="row">
    <div class="col-12 font mt-5 ms-5">
        <p class="h1" style="font-weight: bold;">Detalles de Articulo</p>
    </div>
</div>

<div class="row mt-5 px-5">
    <div class="row px-5">
        <div class="col-4">
            <h4>Numero de Articulo</h4>
            <h5 class="font" style="font-weight: bold;">{{$articulo->pivot->id_articulo_clinica}}</h5>
        </div>
        <div class="col-4">
            <h4>Nombre</h4>
            <h5 class="font" style="font-weight: bold;">{{$nombreArticulo}}</h5>
        </div>
        <div class="col-4">
            <h4>Estado</h4>
            <h5 class="font" style="font-weight: bold;">{{$articulo->pivot->estado}}</h5>
        </div>
    </div>
    
    <div class="row mt-5 px-5">
        <div class="col-4">
            <h4>Stock Actual</h4>
            <h5 class="font" style="font-weight: bold;">{{$articulo->pivot-> lotes_disponibles}}</h5>
        </div>
        <div class="col-4">
            <h4>Stock Minimo <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#cambiarMinimos"></i></h4>
            <h5 class="font" style="font-weight: bold;">{{$articulo->pivot->stock_minimo}}</h5>
        </div>
        <div class="col-4">         
            <h4>Pedido Automatico <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#cambiarAutomatico"></i></h4>
            @if($articulo->pivot->pedido_automatico == 0)
                <h5 class="font" style="font-weight: bold;">Desactivado</h5>
            @endif
            @if($articulo->pivot->pedido_automatico == 1)
                <h5 class="font" style="font-weight: bold;">Activado</h5>
            @endif
        </div>
    </div>
</div>

    <div class="col-12 px-5">
            <div class="row mt-5">
                <div class="col-6 mb-5 ms-3"> 
                    <div class="row font">
                        <div class="col-2 text-center enabled" id="pedidos">
                                <span>Pedidos</span>
                            </div>
                            <div class="col-2 text-center disabled" id="solicitudes">
                                <span>Solicitudes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div id="tablaPedidos">
            <div class="row">
                <div class="col-10 font px-5">
                    <table class="table table-hover" id="pedidos-table">
                        <thead>
                            <tr>
                            <th>NºPedido</th>
                            <th>Lotes Recibidos</th>
                            <th>Acciones</th>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($pedidosProcesados as $pedido)
                                    <tr>
                                        <td>{{$pedido['id_pedido']}}</td>
                                        <td>{{$pedido['lotes_recibidos']}}</td>
                                        <td><a href="/pedidos/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_pedido']}}"><i class="fa-solid fa-eye"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        <div id="tablaSolicitudes">
            <div class="row">
                    <div class="col-10 font px-5">
                        <table class="table table-hover" id="solicitudes-table">
                            <thead>
                                <tr>
                                <th>NºPedido</th>
                                <th>Usuario Solicitante</th>
                                <th>Lotes Recibidos</th>
                                <th>Acciones</th>
                                </tr>
                            </thead>
                                <tbody>
                                    @foreach($solicitudesProcesadas as $pedido)
                                        <tr>
                                            <td>{{$pedido['id_solicitud']}}</td>
                                            <td>{{$pedido['nombre']}}</td>
                                            <td>{{$pedido['lotes_recibidos']}}</td>
                                            <td><a href="/solicitudes/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_solicitud']}}"><i class="fa-solid fa-eye"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>

<div class="modal fade" id="cambiarMinimos" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-2 m-5">
            <p class="font fs-2 mb-5 text-center">Stock Minimo</p>

            <div class="row">

                <div class="col-6">
                    <h3 class="fs-5" style="font-weight: 500;">Stock Minimo Actual</h3>
                    <h5 class="font" style="font-weight: bold;">{{$articulo->pivot->stock_minimo}}</h5> 
                </div>

                <div class="col-6">
                    <h3 class="fs-5" style="font-weight: 500;">Stock A Pedir</h3>
                    <form id="form1" action="/inventario/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$articulo->pivot->id_articulo_clinica}}/cambiar-minimo" method="POST">
                        @csrf
                        @method('POST')   
                        <input class="form-control" type="number" name="minimo" id="numeroMinimo" min="1" value="1" onInput="comprobar(event)">
                        <h6 style="display:none;" id="error" class="text-danger">Ingresa Cantidad Valida</h6>
                    </form>
                </div>

                <div class="row">
                    <div class="col-12 d-flex justify-content-between mt-4">
                        <button type="button" class="btn-cancelar" onclick="resetMinimo()" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="ms-3 btn-cancelar" onclick="verificarMinimos()">Guardar</button>
                    </div>
                </div>
                
            </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="cambiarAutomatico" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
            <div class="modal-body p-2 m-5">
            @if($articulo->pivot->pedido_automatico == 0)
                <p class="font fs-2 mb-5 text-center">Pedido Automatico: Desactivado</p> 
            @endif
            @if($articulo->pivot->pedido_automatico == 1)
                <p class="font fs-2 mb-5 text-center">Pedido Automatico: Activado</p> 
            @endif

                        <form id="form2" action="/inventario/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$articulo->pivot->id_articulo_clinica}}/pedido-automatico" method="POST">
                        @csrf
                        @method('POST') 
                            <div class="row">
                                <div class="col-6">
                                    <h3 class="fs-5" style="font-weight: 500;">Estado:</h3>
                                        <select class="form-control" name="automatico" id="seleccionAutomatica" onchange="cambiarEstado(event)">
                                            <option value="0">Desactivado</option>
                                            <option value="1">Activado</option>
                                        </select>
                                </div> 
                                <div class="col-6">
                                    <h3 class="fs-5" style="font-weight: 500;">Stock a Pedir:</h3>
                                    @if($articulo->pivot->pedido_automatico == 0)
                                    <input class="form-control" type="number" name="cantidad" min="1" value={{$lotesAutomaticos}} id="cantidadAutomatica" disabled onInput="comprobar(event)">
                                    @endif
                                    <input style="display:none;" type="number" name="id_usuario" value="{{Auth::guard('usuario')->user()->id_usuario}}">
                                    @if($articulo->pivot->pedido_automatico == 1)
                                    <input class="form-control" type="number" name="cantidad" min="1" value={{$lotesAutomaticos}} id="cantidadAutomatica" onInput="comprobar(event)">
                                    @endif
                                    <h6 style="display:none;" id="error2" class="text-danger">Ingresa Cantidad Valida</h6>
                                </div>
                            </div> 
                        </form>

                <div class="row">
                    <div class="col-12 d-flex justify-content-between mt-4">
                        <button type="button" class="btn-cancelar" data-bs-dismiss="modal" onclick="resetAutomatico()">Cerrar</button>
                        <button type="submit" class="ms-4 btn-cancelar" onclick="verificarAutomaticos()">Guardar</button>
                    </div>
                </div>
            </div>  
      </div>
    </div>
</div>

<style>
    .modal-body{
    background-color: #DCECFB;
}
.modal-content{
    background-color: #DCECFB;
}
</style>

<script>
        var dtOptions = {
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                    emptyTable: ''
                },
                pagingType: "numbers",
                info: false,
                dom: 'rt',
                scrollY: '350px',
            };

       tabla1 = new DataTable('#pedidos-table', dtOptions);
       tabla2 = new DataTable('#solicitudes-table', dtOptions);
       $("#tablaSolicitudes").hide();

       $('#seleccionAutomatica option[value="{{$articulo->pivot->pedido_automatico}}"]').attr('selected', 'selected');

       $("#solicitudes").on("click", function(){
            $("#tablaSolicitudes").show();
            $('#pedidos').removeClass("enabled").addClass("disabled");
            $('#solicitudes').removeClass("disabled").addClass("enabled");
            $('#pedidos-table_wrapper').hide();
            $('#solicitudes-table_wrapper').show();
            tabla2.columns.adjust().draw();
        })

        $("#pedidos").on("click", function(){
            $('#pedidos').removeClass("disabled").addClass("enabled");
            $('#solicitudes').removeClass("enabled").addClass("disabled");
            $('#solicitudes-table_wrapper').hide();
            $('#pedidos-table_wrapper').show();
            tabla1.columns.adjust().draw();
        })

        function comprobar(evento){
            if (evento.target.value < 1) {
                $("#error").show();
                $("#error2").show();
            } else {
                $("#error").hide();
                $("#error2").hide();
            }
        }

        function resetMinimo(){
            $("#numeroMinimo").val("1");
        }

        function resetAutomatico(){
            $("#cantidadAutomatica").val("{{$lotesAutomaticos}}");
        }

        function verificarAutomaticos(){
            var modal2 = new bootstrap.Modal(document.getElementById('cambiarAutomatico'));
            if ($("#cantidadAutomatica").val() > 0 && $("#seleccionAutomatica").val() == 1) {
                enviarAutomatico();
                $("#error").hide();
                resetAutomatico();
                console.log("adios")
                modal2.toggle();
            } else if($("#seleccionAutomatica").val() == 0){
                enviarAutomatico();
                $("#error").hide();
                resetAutomatico();
                console.log("Hola")
                modal2.toggle();
            }
        }

        function verificarMinimos(){
            if ($("#numeroMinimo").val() > 0) {
                var modal1 = new bootstrap.Modal(document.getElementById('cambiarMinimos'));
                enviarMinimos();
                $("#error").hide();
                modal1.toggle();
            } 
        }

        function cambiarEstado(evento){
            if (evento.target.value == "0") {
                $("#cantidadAutomatica").prop("disabled", true);
            } else {
                $("#cantidadAutomatica").prop("disabled", false);
            }
        }

        function enviarMinimos(){
            document.querySelector("#form1").submit();
        }

        function enviarAutomatico(){
            document.querySelector("#form2").submit();
        }
</script>

@endsection