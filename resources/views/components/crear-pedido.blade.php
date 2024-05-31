@extends('layouts.plantilla')

@section('content')


<div class="row mt-4">
     <div class="col-12 p-0">
       <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </div>
</div>

<div class="row">
    <div class="col-12 mt-5 ms-5">
        <p class="title color">Crear Pedido</p>
    </div>
</div>

<div class="row d-flex justify-content-start">
		    <div class="col-12 px-5 color text-center mt-3">
                <div class="d-flex">
                     <div class="col-6 d-flex justify-content-center py-2 active-paso" id="paso-barra-1">
                            <span>
                                <i class="fa-solid fa-1 mx-2"></i>
                                Seleccion de Datos 
                            </span>
                        </div>  
                        <div class="col-6 d-flex justify-content-center py-2 non-active-paso" id="paso-barra-2">
                            <span>
                                <i class="fa-solid fa-2 mx-2"></i>
                                Confirmar Datos
                            </span>
	                    </div> 
                </div>
		</div>
</div>

<div class="row">
    <div class="col-12 mt-5 ms-5">
        <p class="h5">Escoja los productos</p>
    </div>
</div>

<div class="row mt-4">
	<div class="col-12 px-5">
			<div class="row" id="principal">
				<div class="col-6">
                    <input type="checkbox" onclick="filtrar(event)"> Mostrar Articulos En Minimos
                    <table class="table table-hover" id="articulos-table">
                        <thead>
                            <tr>
                                <th>Articulos</th>
                                <th>Categoria</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rdo as $articulo)
                            <tr>
                                <td class="nombre_articulo" id="{{$articulo['id_articulo']}}" data-estado="{{$articulo['estado']}}" data-cat="{{$articulo['nombre_categoria']}}" data-stock="{{$articulo['stock_actual']}}">{{$articulo['nombre']}}</td>
                                <td>{{$articulo['nombre_categoria']}}</td>
                                <td>
                                <i class="fa-solid fa-plus fa-2x meter" data-bs-toggle="modal" data-bs-target="primero"></i>
                                @if($articulo['estado'] == 'En Minimos')
                                <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
                                @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
				</div>
				
				<div class="col-6 mt-4">
                    <table class="table table-hover" id="articulos-seleccionados-table">
                            <thead>
                                <tr>
                                    <th>Articulos</th>
                                    <th>Numero de Lotes</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--Aqui van todos tus articulos :)-->
                            </tbody>
                    </table>
				</div>

                <div class="col-5">
                    <table class="table table-hover" id="articulos-finales-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Numero de Lotes</th>
                                    <th>Categoria</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--Aqui van todos tus articulos :)-->
                            </tbody>
                    </table>
                </div>
			</div>
	    </div>
</div>

<div class="row mt-5">
    <div class="col-6 px-5">
        <a href="/pedidos/{{Auth::guard('usuario')->user()->servicio->id_servicio}}" class="btn-cancelar">Cancel</a>
    </div>
      <div class="col-6 px-5 d-flex justify-content-end">
            <button class="mx-2 btn-cancelar" id="anterior" onclick="texto('anterior')">Anterior</button>
            <h6 style="display:none;" id="error2" class="text-danger">*Ingresa primero un articulo para poder continuar</h6>
            <button class="mx-2 btn-cancelar" id="siguiente" onclick="texto('siguiente')">Siguiente</button>
            <button class="mx-2 btn-cancelar" type="submit" id="confirmar">Confirmar</button>
      </div>
</div> 

<div class="modal fade" id="confirmarPedido" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-5">
            <p class="font fs-4">¿Esta seguro de querer aceptar esta solicitud?</p>
            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-between">
                    <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                    <button style="margin-left: 50px" type="submit" class="btn-cancelar" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#successModal">Confirmar</button>
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
                            <p>Pedido Registrado Exitosamente</p>
                        </div>
                        <div class="row modal-footer-personalizado">
                        <button type="button" class="btn-cancelar" data-bs-dismiss="modal" onclick="enviarDatos()">Confirmar</button>
                    </div>
                </div>
            </div>
       </div>
 </div>

<form style="display:none;" action="/pedidos/subir-pedido/{{Auth::guard('usuario')->user()->id_usuario}}" method="POST">
@csrf
@method('POST')
</form>

<div class="modal fade" id="primero" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #DCECFB;">
      <div class="modal-body p-2 m-5">
                <p class="font fs-2 mb-5">Detalles</p>

                <div class="row">
                    <div class="col-6">
                        <h3 class="fs-4" style="font-weight: 500;">Nombre del Articulo</h3>
                        <h5 class="font" id="nombre"></h5>
                    </div>
                    <div class="col-6">
                        <h3 class="fs-5" style="font-weight: 500;">Categoria</h3>
                        <h5 class="font" id="categoria"></h5>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-6">
                        <h3 class="fs-4" style="font-weight: 500;">Stock Actual</h3>
                        <h5 class="font" id="stock_actual"></h5>
                    </div>
                    <div class="col-6">
                        <h3 class="fs-5" style="font-weight: 500;">Cantidad</h3>
                        <input class="form-control" type="number" id="cantidad" min="0" value="1">
                        <h6 style="display:none;" id="error" class="text-danger">Ingresar Cantidad Valida</h6>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="button" class="btn-cancelar" data-bs-dismiss="modal" onclick="reset()">Close</button>
                        <button type="button" class="btn-cancelar ms-4" id="agregar">Save changes</button>
                    </div>
                </div>
        
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $("#confirmar").hide();
    $("#anterior").hide();
    $(".col-5").hide();
    var dtOptions = {
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                emptyTable: ''
            },
            pagingType: "numbers",
            info: false,
            scrollY: '350px',
            dom: 'rt',
        };
        
        var posicionFormulario = 0;

        new DataTable('#articulos-table', dtOptions);
        new DataTable('#articulos-seleccionados-table', dtOptions);
        new DataTable('#articulos-finales-table', dtOptions);
        var modal1 = new bootstrap.Modal(document.getElementById('primero'));
        var modal2 = new bootstrap.Modal(document.getElementById('confirmarPedido'));

        $(".meter").on( "click", function(){
            let datos = $(this).parent().siblings(".nombre_articulo");
            $("#nombre").append(datos[0].innerText);
            $("#nombre").attr("id_articulo", datos[0].id);
            $("#categoria").append($(this).parent().siblings(".nombre_articulo").data("cat"));
            $("#stock_actual").append($(this).parent().siblings(".nombre_articulo").data("stock"));
            modal1.toggle();
        });

        $("#confirmar").on("click", function(){
            modal2.toggle();
        });

        function enviarDatos(){
            document.querySelector("form").submit();
        }

        function filtrar(event){

            if (event.target.checked) {
                $("[data-estado='En Stock']").parent().hide();
                $("[data-estado='N/A']").parent().hide();
            } else {
                $("[data-estado='En Stock']").parent().show();
                $("[data-estado='N/A']").parent().show();
            }

            
        }

        function reset(){
            $("#nombre").removeClass();
            $("#nombre").text("");
            $("#cantidad").val("1");
            $("#categoria").text("");
            $("#stock_actual").text("");
            $("#error").hide();
        };
        
        $("#agregar").on("click", function(){
            let cuerpo = $("#articulos-seleccionados-table").find("tbody");
            if ($("#cantidad").val() > 0) {
                $("#error").hide();
                if (cuerpo.find("#"+$("#nombre").attr("id_articulo")).length == 0) {
                    $('#articulos-seleccionados-table').DataTable().destroy();                                                                                                                                        
                    cuerpo.append("<tr id="+$("#nombre").attr("id_articulo")+"><td data-cat='"+ $("#categoria").text() +"' data-stock='"+ $("#stock_actual").text() +"' class='articuloActual'>" + $("#nombre").text() + "</td><td class='cantidadActual'>" + $("#cantidad").val() + "</td><td><i class='fa-solid fa-plus fa-1x me-2 mas1'></i><i class='fa-solid fa-minus me-2 menos1'></i><i class='fa-solid fa-file verDetalles me-2' data-bs-toggle='modal' data-bs-target='primero'></i><i class='fa-solid fa-trash me-2 eliminar'></i></td></tr>");
                    $('#articulos-seleccionados-table').DataTable(dtOptions);
                    reset();
                    modal1.toggle();
                    acciones();
                } else {
                    if ($("#cantidad").val() == 0) {
                        $('#articulos-seleccionados-table').DataTable().destroy();
                        cuerpo.find("#"+$("#nombre").attr("id_articulo")).remove();
                        $('#articulos-seleccionados-table').DataTable(dtOptions);
                        reset();
                        modal1.toggle();
                    } else {
                        cuerpo.find("#"+$("#nombre").attr("id_articulo")).find(".cantidadActual").text($("#cantidad").val());
                        reset();
                        modal1.toggle();
                    }
                }
            } else {
                $("#error").show();
            }
        })

        function acciones(){
            $( ".mas1").unbind( "click" );
            $( ".menos1").unbind( "click" );
            $( ".verDetalles").unbind( "click" );
            $( ".eliminar").unbind( "click" );

            $(".mas1").on( "click", function(){
            let datos = parseInt($(this).parent().siblings(".cantidadActual").text())+1;
            $(this).parent().siblings(".cantidadActual").text(datos);
            });

            $(".menos1").on( "click", function(){
            let datos = parseInt($(this).parent().siblings(".cantidadActual").text())-1;
            if (datos < 1) {
                    $(this).siblings(".eliminar").trigger("click");
                } else {
                    $(this).parent().siblings(".cantidadActual").text(datos);
                }
            
            });

            $(".eliminar").on( "click", function(){
                $('#articulos-seleccionados-table').DataTable().row($(this).parent().parent()).remove();
                $('#articulos-seleccionados-table').DataTable().destroy();
                $('#articulos-seleccionados-table').DataTable(dtOptions);
            });

            $(".verDetalles").on( "click", function(){
                let datos = $(this).parent().siblings(".articuloActual");
                $("#nombre").append(datos[0].innerText);
                $("#nombre").attr("id_articulo", $(this).parent().parent().attr("id"));
                $("#nombre").addClass(datos[0].id);
                $("#cantidad").val(parseInt($(this).parent().siblings(".cantidadActual").text()))
                $("#categoria").append($(this).parent().siblings(".articuloActual").data("cat"));
                $("#stock_actual").append($(this).parent().siblings(".articuloActual").data("stock"));
                modal1.toggle();
            });
        }

        function texto(posicionNueva){
            if ($("#articulos-seleccionados-table").find("td").hasClass("dataTables_empty") == false) {
                $("#error2").hide();
                if (posicionNueva == "anterior" && posicionFormulario > 0) {
                posicionFormulario--;
                } else if(posicionNueva == "siguiente" && posicionFormulario < 2) {
                posicionFormulario++;
                } 
    
                if (posicionFormulario == 0) {
                $("#principal").find(".col-6").show();
                $("#anterior").hide();
                $("#confirmar").hide();
                $("#siguiente").show();
                $(".col-5").hide();
                $("#paso-barra-2").removeClass("active-paso").addClass("non-active-paso");
                limpiarDatosFinales();
                } else {
                $("#principal").find(".col-6").hide();
                $("#confirmar").show();
                $("#anterior").show();
                $("#siguiente").hide();
                $(".col-5").show();
                $("#paso-barra-2").addClass("active-paso").removeClass("non-active-paso");
                datosFinales();
                }
            } else {
                $("#error2").show();
            }
        }

        function datosFinales(){
            let nombresArticulos = $("#articulos-seleccionados-table").find("tbody").find(".articuloActual");
            let cantidades = $("#articulos-seleccionados-table").find("tbody").find(".cantidadActual");
            let idArticulos = $("#articulos-seleccionados-table").find("tbody").find("tr");
            
            $('#articulos-finales-table').DataTable().destroy();
            for (let index = 0; index < nombresArticulos.length; index++) {
                $("#articulos-finales-table").find("tbody").append("<tr><td>"+ $(nombresArticulos[index]).text() +"</td><td>"+ $(cantidades[index]).text() +"</td><td>"+ $(nombresArticulos[index]).data("cat") +"</td></tr>");
                $("form").append("<input type='text' name='"+$(idArticulos[index]).attr("id")+" 'value='"+ $(cantidades[index]).text() +"'>");
            }
            $('#articulos-finales-table').DataTable(dtOptions);
        }

        function limpiarDatosFinales(){
            $('#articulos-finales-table').DataTable().destroy();
            $('#articulos-finales-table').find("tbody").children().remove();
            $('#articulos-finales-table').DataTable(dtOptions);
            $("form").children().remove();
        }

</script>


@endsection

