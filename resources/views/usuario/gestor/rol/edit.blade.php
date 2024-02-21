<!-- bootstrap  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- Fontawesome  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- css  -->
<link rel="stylesheet" href="{{ asset('css/gestor.css') }}">



<div class="container-fluid font">
    <div class="row" >
        <div class="col-2">
            @include('usuario.gestor.nav')
        </div>
        <div class="col-10 p-0">
            <div class="row mt-4">
                <div class="col-12 p-0">
                    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 px-5">
                    <h1 class="title">Modificar Rol</h1>
                </div>
            </div>
            @if($errors->any())
                @isset($errors)
                <div class="row mt-4">
                    <div class="col-12 px-5">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        
                    </div>
                </div>
                @endisset
            @endif
           
           
            <div class="row mt-4">
                <div class="col-12 px-5">
                    <form method="POST" action="{{ route('rol.modificar' , ['id' => $rol->id_rol]) }}">
                    @csrf  
                            <div class="row mt-4  ">
                                <div class="col-12">
                                    <span class="titulo-fomulario-add-usuario">Introduce los datos.</span>
                                </div>
                            </div>
                            <div class="row d-flex flex-column align-items-center">
                                <div class="col-4">
                                    <label for="nombre_servicio" class="my-3 label-personalizado">Nombre del Rol</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-style"  name="nombre_rol" id="nombre_rol" value="{{ $rol->nombre }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <div class="row mt-5">
                        <div class="col-12 d-flex justify-content-end">
                            <input type="submit" class="btn-pasos mx-2" value="Modificar" id="confirmar">
                        </div>
                    </div>
                    </form>
                </div>
            
            </div>
        </div>
    </div>
</div>