@extends('layouts.plantilla')

@section('content')

    <div class="row">
        <div class="row mt-4">
            <div class="col-12 mt-5">
                <h1 class="title ms-5">Solicitudes</h1>
            </div>
        </div>

        <div class="col-12 px-5">
            <div class="row mt-5">
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
                                    <td><a href="/solicitudes/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_pedido']}}"><i class="fa-solid fa-eye"></i></a> <i class="fa-regular fa-square-check" onclick="mirar({{$pedido['id_pedido']}})"></i></td>
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
                                    <td><a href="/solicitudes/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_pedido']}}"><i class="fa-solid fa-eye"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="modal fade" id="cambiarMinimos" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body p-5">
            <p class="font fs-4">Los siguientes articulos se encuentran en stocks minimos, escoga la cantidad oportuna:</p>

                    
            <div class="row py-5">
                <div class="col-12">
                    <table class="table table-hover" id="minimos-table">
                        <thead>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                        </thead>
                        <tbody style="background-color: white;">
                        <!--- <tr id='id_articulo'>--->
                            <!---<td> Nombre </td>--->
                            <!---<td class='cantidad'> Cantidad </td>--->
                        <!---</tr>--->
                        </tbody>
                    </table>
                </div>
            </div>
            
            
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <button type="button" class="btn-cancelar me-5" data-bs-dismiss="modal" onclick="limpiar()">Cancelar</button>
                        <button style="margin-left: 150px" type="submit" class="btn-cancelar " data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#confirmarMinimos">Confirmar</button>
                    </div>
                </div>  

            </div>
      </div>
    </div>
</div>
 
<div class="modal fade" id="confirmarMinimos" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-5">
            <p class="font fs-4">¿Esta seguro de querer aceptar esta solicitud?</p>

            <p style="display:none;" id="modalConfirmar">Aqui hay un id</p>

            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-between">
                    <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                    <button style="margin-left: 50px" type="submit" class="btn btn-cancelar" data-bs-dismiss="modal" onclick="enviarDatos(true)">Confirmar</button>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="confirmar" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-5">
            <p class="font fs-4">¿Esta seguro de querer aceptar esta solicitud?</p>

            <p style="display:none;" id="modalConfirmar">Aqui hay un id</p>

            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-between">
                    <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                    <button style="margin-left: 50px" type="submit" class="btn btn-cancelar" data-bs-dismiss="modal" onclick="enviarDatos(false)" data-bs-toggle="modal" data-bs-target="#successModal">Confirmar</button>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="successModalLabel" aria-modal="true" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-body rounded-3">
                        <div class="row modal-header-personalizado">
                            <i class="fa-solid fa-circle-check modal-icon"></i>
                        </div>
                        <div class="row modal-body">
                            <p>Solicitud Aceptada Exitosamente</p>
                        </div>
                        <div class="row modal-footer-personalizado">
                        <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal">Confirmar</button>
                    </div>
                </div>
            </div>
       </div>
 </div>

    <form style="display:none;" method="POST">
    @csrf
    @method('POST')
    </form>

<style>
    #minimos-table_wrapper{
        width: 450px;
    }
</style>

<script type="text/javascript">

    function mirar(entrada){
        var modal1 = new bootstrap.Modal(document.getElementById('cambiarMinimos'));
        var modal2 = new bootstrap.Modal(document.getElementById('confirmar'));
        $.ajax({
        url: "/solicitudes/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/"+entrada+"/comprobar-minimos",
        type: 'GET',
        dataType: "json",
        success: function(data) {
            console.log(data)
            if (data.length > 1) {
                $('#minimos-table').DataTable().destroy();
                    for (let index = 0; index < data.length-1; index++) {
                        $("#minimos-table").find("tbody").append("<tr id="+data[index]["id_articulo"]+"><td>"+ data[index]["nombre_articulo"] +"</td><td> <input type='number' class='cantidad form-control' min='0' max='"+data[index]["lotes_disponibles"]+"' value='"+ data[index]["lotes_disponibles"] +"'></td></tr>");
                        $("#minimos-table").find("tbody").addClass(""+data[data.length-1]+"");
                    }
                $('#minimos-table').DataTable(dtOptions);
                modal1.toggle();
            } else {
                $("#confirmar").find("#modalConfirmar").addClass(""+entrada+"");
                modal2.toggle();
            }
        }
    });
    }

    function enviarDatos(minimos){
        let id_solicitud = "";
        if (minimos == true) {
            let contenido = $("#minimos-table").find("tbody").children();
            for (let index = 0; index < contenido.length; index++) {
            $("form").append("<input type='number' name='" +$(contenido[index]).attr('id')+ "' value='" +$(contenido[index]).find('.cantidad').val()+"'>"); 
            }
        
            id_solicitud = $("#minimos-table").find("tbody").attr("class");
        } else {
            console.log($("#confirmar").find("#modalConfirmar").attr("class"));
            id_solicitud = $("#confirmar").find("#modalConfirmar").attr("class");
        }

        $("form").attr("action", "/solicitudes/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/"+id_solicitud+"/aceptar-solicitud");
       document.querySelector("form").submit();
    }

    function limpiar(){
        $('#minimos-table').DataTable().destroy();
        $("#minimos-table").find("tbody").find("tr").remove();
        $('#minimos-table').DataTable(dtOptions);
    }

    var dtOptions = {
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                emptyTable: ''
            },
            pagingType: "numbers",
            info: false,
            dom: 'rt',
        };
        new DataTable('#pedidos-pendientes-table', dtOptions);
        new DataTable('#pedidos-aceptados-table', dtOptions);
        new DataTable('#minimos-table');
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