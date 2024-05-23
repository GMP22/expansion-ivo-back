@extends('layouts.plantilla')
@section('content')
<div class="row">
    <div class="col-12 font mt-5 ms-5">
        <p class="h1">Detalles de Articulo</p>
    </div>
</div>

<div class="row">
    <div class="row">
        <div class="col-4">
            <h2>Numero de Articulo</h2>
            <h3>{{$articulo->pivot->id_articulo_clinica}}</h3>
        </div>
        <div class="col-4">
            <h2>Nombre</h2>
            <h3>{{$nombreArticulo}}</h3>
        </div>
        <div class="col-4">
            <h2>Estado</h2>
            <h3>{{$articulo->pivot->estado}}</h3>
        </div>
    </div>
    
    <div class="row">
        <div class="col-4">
            <h2>Stock Actual</h2>
            <h3>{{$articulo->pivot-> lotes_disponibles}}</h3>
        </div>
        <div class="col-4">
            <h2>Stock Minimo <button data-bs-toggle="modal" data-bs-target="#cambiarMinimos">Cambiar Minimo</button> </h2>
            <h3>{{$articulo->pivot->stock_minimo}}</h3>
        </div>
        <div class="col-4">
            <h2>Pedido Automatico <button data-bs-toggle="modal" data-bs-target="#cambiarAutomatico">Hacer Automatico</button></h2>
            <h3>{{$articulo->pivot->pedido_automatico}}</h3>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12 font mt-5 ms-5">
        <table class="table table-hover">
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
                    <td><button type="button"><a href="/pedidos/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$pedido['id_pedido']}}">Ver Pedido</a></button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="cambiarMinimos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        </div>
            <div>
             <p>Minimo Actual: {{$articulo->pivot->stock_minimo}}</p> 
             <form action="/inventario/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$articulo->pivot->id_articulo_clinica}}/cambiar-minimo" method="POST">
             @csrf
@method('POST')   
             <input class="input-group" type="number" name="minimo" id="numeroMinimo" [min]="0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Cambiar Minimo</button>
             </form>
            </div>  
      </div>
    </div>
</div>

<div class="modal fade" id="cambiarAutomatico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        </div>
            <div>
             <p>Pedido Automatico: {{$articulo->pivot->pedido_automatico}}</p> 
             <form action="/inventario/{{Auth::guard('usuario')->user()->servicio->id_servicio}}/{{$articulo->pivot->id_articulo_clinica}}/pedido-automatico" method="POST">
             @csrf
             @method('POST')   
                <select name="automatico">
                    <option value="0" selected>Desactivado</option>
                    <option value="1">Activado</option>
                </select>

                <input style="display:none;" type="number" name="id_usuario" value="{{Auth::guard('usuario')->user()->id_usuario}}">

                <input class="input-group" type="number" name="cantidad" min="1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
             </form>
            </div>  
      </div>
    </div>
</div>




@endsection