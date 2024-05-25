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
                                    <td><button type="button"><a href="/pedidos/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_pedido']}}">Ver Detalles</a></button> <button type="button" onclick="mirar({{$pedido['id_pedido']}})">Aceptar Solicitud</button></td>
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

            <table class="table table-hover" id="minimos-table">
                <thead>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                </thead>
                <tbody >
                <!--- <tr id='id_articulo'>--->
                    <!---<td> Nombre </td>--->
                    <!---<td class='cantidad'> Cantidad </td>--->
                <!---</tr>--->
                </tbody>
            </table>


            <div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" onclick="enviarDatos()">Confirmar</button>
            </div>  
      </div>
    </div>
</div>
            
    <form style="display:none;" method="POST">
    @csrf
    @method('POST')
    </form>


<script type="text/javascript">
    function mirar(entrada){
        var modal1 = new bootstrap.Modal(document.getElementById('cambiarMinimos'));
        $.ajax({
        url: "/solicitudes/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/"+entrada+"/comprobar-minimos",
        type: 'GET',
        dataType: "json",
        success: function(data) {
            console.log(data)
            if (data.length > 1) {
                $('#minimos-table').DataTable().destroy();
                    for (let index = 0; index < data.length-1; index++) {
                        $("#minimos-table").find("tbody").append("<tr id="+data[index]["id_articulo"]+"><td>"+ data[index]["nombre_articulo"] +"</td><td> <input type='number' class='cantidad' min='0' max='"+data[index]["lotes_disponibles"]+"' value='"+ data[index]["lotes_disponibles"] +"'></td></tr>");
                        $("#minimos-table").find("tbody").addClass(""+data[data.length-1]+"");
                    }
                $('#minimos-table').DataTable(dtOptions);
                modal1.toggle();
            } 
        }
    });
    }

    function enviarDatos(){
        let contenido = $("#minimos-table").find("tbody").children();
        for (let index = 0; index < contenido.length; index++) {
            $("form").append("<input type='number' name='" +$(contenido[index]).attr('id')+ "' value='" +$(contenido[index]).find('.cantidad').val()+"'>"); 
        }
        
        let id_solicitud = $("#minimos-table").find("tbody").attr("class");

        $("form").attr("action", "/solicitudes/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/"+id_solicitud+"/aceptar-solicitud");
       document.querySelector("form").submit();
    }

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
        new DataTable('#minimos-table', dtOptions);
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