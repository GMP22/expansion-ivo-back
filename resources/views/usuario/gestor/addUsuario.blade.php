<!-- bootstrap  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- Fontawesome  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- css  -->
<link rel="stylesheet" href="{{ asset('css/gestor.css') }}">
<!-- script javascript  -->
<script type="text/javascript" language="javascript" src="{{ asset('js/usuarioFormulario.js') }}"></script>


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
                    <h1 class="title color">Alta Usuario</h1>
                </div>
            </div>
            <div id="errores-js" class="row mt-4" style="display: none;">
                <div class="col-12 px-5">
                    <div class="alert alert-danger">
                        <ul id="lista-errores">
                           
                        </ul>
                    </div>
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
            @if(session('success'))
                <div class="modal-backdrop fade show"></div>
            @endif
        
            <div class="modal fade  mt-5 {{ session('success') ? ' show d-block' : '' }}" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-modal="true" aria-hidden="true">
                <div class="modal-dialog " role="document">
                    <div class="modal-content">
                    <div class="modal-header-personalizado modal-style">
                        <i class="fa-solid fa-circle-check modal-icon"></i>
                    </div>
                    <div class="modal-body  modal-style">
                        @if(session('success'))
                        <p>{{ session('success') }}</p>
                        @endif
                    </div>
                    <div class="modal-footer-personalizado modal-style">
                        <a href="{{ route('gestor.add-usuario')}}">Cerrar</a>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 px-5 color">
                    <div class="d-flex">
                       <div class="col-4 d-flex justify-content-center py-2 active-paso" id="paso-barra-1">
                            <span>
                                <i class="fa-solid fa-1 mx-2"></i>
                                Datos Personales
                            </span>
                        </div> 
                        <div class="col-4 d-flex justify-content-center py-2 non-active-paso" id="paso-barra-2">
                            <span>
                                <i class="fa-solid fa-2 mx-2"></i>
                                Crear Cuenta
                            </span>
                        </div>  
                        <div class="col-4 d-flex justify-content-center py-2 non-active-paso" id="paso-barra-3">
                            <span>
                                <i class="fa-solid fa-3 mx-2"></i>
                                Confirmar Datos
                            </span>
                        </div> 
                    </div>
                </div>
            </div>
        
            <div class="row mt-4">
                <div class="col-12 px-5">
                    <form method="POST" action="{{ route('usuario.crear') }}">
                    @csrf 
                    <div id="paso-1">
                            <div class="row mt-4">
                                <div class="col-12">
                                    <span class="titulo-fomulario-add-usuario">Introduce los datos personales.</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="dni" class="my-3 label-personalizado">DNI</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-style"  name="dni" id="dni" autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="nombre" class="my-3 label-personalizado">Nombre</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-style"  name="nombre" id="nombre">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="apellido1" class="my-3 label-personalizado">Primer Apellido</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-style"  name="apellido1" id="apellido1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="apellido2" class="my-3 label-personalizado">Segundo Apellido</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-style"  name="apellido2" id="apellido2">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="nombre" class="my-3 label-personalizado">Rol</label>
                                    <div class="form-group">
                                        <select class="form-group form-select" id="rol" name="rol">
                                            @foreach($rols as $rol)
                                                <option value="{{$rol->id_rol}}">{{$rol->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="col-4">
                                    <label for="apellido1" class="my-3 label-personalizado">Servicio</label>
                                    <div class="form-group">
                                        <select class="form-group form-select" name="servicio" id="servicio">
                                            @foreach($servicios as $servicio)
                                                <option value="{{$servicio->nombre_servicio}}">{{$servicio->nombre_servicio}}</option>
                                            @endforeach
                                            <option value="0">Ninguno</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="dni" class="my-3 label-personalizado">Sexo</label>
                                    <div class="form-group">
                                            <select class="form-group form-select" id="sexo" name="sexo">
                                                <option value="Hombre">Hombre</option>
                                                <option value="Mujer">Mujer</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="correo" class="my-3 label-personalizado">Correo</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="email" class="form-control input-style" name="correo" id="correo">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="codigoPostal" class="my-3 label-personalizado">Código Postal</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-style"  name="codigoPostal" id="codigoPostal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="direccion" class="my-3 label-personalizado">Dirección</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-style"  name="direccion" id="direccion">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="fechaNac" class="my-3 label-personalizado">Fecha Nacimiento</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="date" class="form-control input-style"  name="fechaNac" id="fechaNac">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="telefono" class="my-3 label-personalizado">Telefono</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-style"  name="telefono" id="telefono">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                    <div id="paso-2">
                        <div class="row mt-4">
                            <div class="col-12">
                                <span class="titulo-fomulario-add-usuario">Crear la cuenta.</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label for="usuario" class="my-3 label-personalizado">Usuario</label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-style"  name="usuario" id="usuario">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                    <label for="correo" class="my-3 label-personalizado">Correo</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-style" id="correo-paso-2" disabled>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label for="password" class="my-3 label-personalizado">Contraseña</label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="password" class="form-control input-style"  name="password" id="password">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                    <label for="password_confirmation" class="my-3 label-personalizado">Confirmacion contraseña</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="password" class="form-control input-style"  name="password_confirmation" id="passwordConfirmar">
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div id="paso-3">
                        <div class="row mt-4">
                            <div class="col-12">
                                <span class="titulo-fomulario-add-usuario">Confirma los datos.</span>
                            </div>
                        </div>
                        <div class="row">
                                    <div class="col-4">
                                        <label for="dni" class="my-3 label-personalizado">DNI</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-style"  id="dniDisabled" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="nombre" class="my-3 label-personalizado">Nombre</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-style"  id="nombreDisabled" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="apellido1" class="my-3 label-personalizado">Primer Apellido</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-style"  id="apellido1Disabled" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="apellido2" class="my-3 label-personalizado">Segundo Apellido</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-style" id="apellido2Disabled" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="nombre" class="my-3 label-personalizado">Rol</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control input-style"  id="rolDisabled" disabled>
                                        </div>
                                        
                                    </div>
                                    <div class="col-4">
                                        <label for="apellido1" class="my-3 label-personalizado">Servicio</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control input-style"  id="servicioDisabled" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="dni" class="my-3 label-personalizado">Sexo</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-style"  id="sexoDisabled" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="correo" class="my-3 label-personalizado">Correo</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="email" class="form-control input-style" id="correoDisabled" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="codigoPostal" class="my-3 label-personalizado">Código Postal</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-style"  id="codigoPostalDisabled" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="direccion" class="my-3 label-personalizado">Dirección</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-style"  id="direccionDisabled" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="fechaNac" class="my-3 label-personalizado">Fecha Nacimiento</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="date" class="form-control input-style"  id="fechaNacDisabled" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="telefono" class="my-3 label-personalizado">Telefono</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-style"  id="telefonoDisabled" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-6 px-5">
                            <a href="{{route('gestor.usuario')}}" class="btn-cancelar">Cancel</a>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <a  class="btn-pasos mx-2" id="anterior">Anterior</a>
                            <a  class="btn-pasos mx-2" id="siguiente">Siguiente</a>
                            <input type="submit" class="btn-pasos mx-2" value="Confirmar" id="confirmar">
                        </div>
                    </div>
                    </form>
                </div>
            
            </div>
        </div>
    </div>
</div>