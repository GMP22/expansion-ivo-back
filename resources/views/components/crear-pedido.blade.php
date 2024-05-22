@extends('layouts.plantilla')

@section('content')
<div class="row">
    <div class="col-12 p-o mt-3">
	        <!--
			      Aqui tenemos que hacer el breadcumb o incluir el breadcumb que originalmente se creó para esto
		      -->
    </div>
</div>

<div class="row">
    <div class="col-12 mt-5 ms-5">
        <p class="h1">Crear Pedido</p>
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
                    <table class="table table-hover" id="articulos-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rdo as $articulo)
                            <tr>
                                <td class="nombre_articulo" id="{{$articulo['id_articulo']}}">{{$articulo['nombre']}}</td>
                                <td><button class="meter" type="button" data-bs-toggle="modal" data-bs-target="primero">Añadir</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
				</div>
				
				<div class="col-6">
                    <table class="table table-hover" id="articulos-seleccionados-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
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
                                    <th>Cantidad</th>
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
      <div class="col-6 d-flex justify-content-end">
            <button class="mx-2" id="anterior" onclick="texto('anterior')">Anterior</button>
            <button class="mx-2" id="siguiente" onclick="texto('siguiente')">Siguiente</button>
            <button class="mx-2" type="submit" id="confirmar">Confirmar</button>
      </div>
</div> 

<form style="display:none;" action="/pedidos/subir-pedido/{{Auth::guard('usuario')->user()->id_usuario}}" method="POST">
@csrf
@method('POST')
</form>

<div class="modal fade" id="primero" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h2 id="nombre"></h2>
        <input type="number" id="cantidad" min="0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="reset()">Close</button>
        <button type="button" class="btn btn-primary" id="agregar">Save changes</button>
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
            info: false
        };
        
        var posicionFormulario = 0;

        new DataTable('#articulos-table', dtOptions);
        new DataTable('#articulos-seleccionados-table', dtOptions);
        new DataTable('#articulos-finales-table', dtOptions);
        var modal1 = new bootstrap.Modal(document.getElementById('primero'));

        $(".meter").on( "click", function(){
            let datos = $(this).parent().siblings(".nombre_articulo");
            $("#nombre").append(datos[0].innerText);
            $("#nombre").attr("id_articulo", datos[0].id);
            modal1.toggle();
        });

        $("#confirmar").on("click", function(){
            document.querySelector("form").submit();
        });

        function reset(){
            $("#nombre").removeClass();
            $("#nombre").text("");
            $("#cantidad").val("");
        };
        
        $("#agregar").on("click", function(){
            let cuerpo = $("#articulos-seleccionados-table").find("tbody");
            if (cuerpo.find("#"+$("#nombre").attr("id_articulo")).length == 0) {
                $('#articulos-seleccionados-table').DataTable().destroy();
                cuerpo.append("<tr id="+$("#nombre").attr("id_articulo")+"><td class='articuloActual'>" + $("#nombre").text() + "</td><td class='cantidadActual'>" + $("#cantidad").val() + "</td><td><button class='mas1' type='button'>Añadir</button><button class='menos1' type='button'>Disminuir</button><button class='verDetalles' type='button' data-bs-toggle='modal' data-bs-target='primero'>Ver Detalles</button><button class='eliminar' type='button'>Eliminar</button></td></tr>");
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
                $("#nombre").addClass(datos[0].id);
                $("#cantidad").val(parseInt($(this).parent().siblings(".cantidadActual").text()))
                modal1.toggle();
            });
        }

        function texto(posicionNueva){
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
                limpiarDatosFinales();
            } else {
                $("#principal").find(".col-6").hide();
                $("#confirmar").show();
                $("#anterior").show();
                $("#siguiente").hide();
                $(".col-5").show();
                datosFinales();
            }
        }

        function datosFinales(){
            let nombresArticulos = $("#articulos-seleccionados-table").find("tbody").find(".articuloActual");
            let cantidades = $("#articulos-seleccionados-table").find("tbody").find(".cantidadActual");
            let idArticulos = $("#articulos-seleccionados-table").find("tbody").find("tr");
            
            $('#articulos-finales-table').DataTable().destroy();
            for (let index = 0; index < nombresArticulos.length; index++) {
                $("#articulos-finales-table").find("tbody").append("<tr><td>"+ $(nombresArticulos[index]).text() +"</td><td>"+ $(cantidades[index]).text() +"</td></tr>");
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

