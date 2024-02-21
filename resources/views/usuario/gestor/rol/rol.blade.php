<!-- bootstrap  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- Fontawesome  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- css  -->
<link rel="stylesheet" href="{{ asset('css/gestor.css') }}">
<!-- dataTables link -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<div class="container-fluid p-0 font">
    <div class="row">
        <div class="col-2 p-0">
            @include('usuario.gestor.nav')
        </div>
        <div class="col-10 p-0 px-5">
            <div class="row mt-4">
                <div class="col-12">
                    <h1 class="title">Rol</h1>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('gestor.add-rol')}}" class="btn-add-rol">Add Rol</a>
                </div>
            </div>
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
                        <a href="{{ route('gestor.rol')}}">Cerrar</a>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12">
                    <table class="table table-bordered" id="usuarios-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Fecha creación</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rols as $rol)
                                <tr>
                                    <td>{{ $rol->nombre }}</td>
                                    <td>{{ $rol->fecha_creacion }}</td>
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ route('rol.edit' , ['id' => $rol->id_rol])}}">
                                            <i class="fa-solid fa-pen-to-square mx-3 color"></i>
                                        </a>
                                        <!-- <form action="{{ route('usuario.destroy', ['id' => $rol->id_rol]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="color delete-btn-style">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form> -->
                                    </td>
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
    $(document).ready(function() {
        new DataTable('#usuarios-table');
    });
</script>