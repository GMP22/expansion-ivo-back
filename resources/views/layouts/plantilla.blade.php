<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- Fontawesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css  -->
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gestor.css') }}">
    <!-- dataTables link -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <title>Document</title>
    <style>
        #logout{
            margin-top: 150%;
        }

        .enabled{
            color: #092C4C !important;
            border-bottom: 2px solid #092C4C;
            font-weight: 900;
            font-size: 1.3rem;
            cursor: pointer;
        }
        .disabled{
            font-size: 1.3rem;
            color: #98A3B3 !important;
            border-bottom: 2px solid #98A3B3;
            cursor: pointer;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-2 nav-style">
            <div class="row pt-5">
                <div class="col-12 text-center">
                    <img src="{{ asset('img/logoIvoBlanco.png') }}" alt="Logo" width="100px" heigth="100px">
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 p-0 d-flex flex-column align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mt-3">
                            <a class="nav-link" href="/solicitudes/{{Auth::guard('usuario')->user()->servicio->id_servicio}}">
                                <i class="fa-solid fa-user mx-2"></i>
                                Solicitudes
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link" href="/pedidos/{{Auth::guard('usuario')->user()->servicio->id_servicio}}">
                                <i class="fa-solid fa-user mx-2"></i>
                                Pedidos
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link" href="/inventario/{{Auth::guard('usuario')->user()->servicio->id_servicio}}">
                                <i class="fa-solid fa-user mx-2"></i>
                                Inventario
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="row h-50" id="logout">
                    <div class="col-12  d-flex align-items-end justify-content-center ">
                            <div class="d-flex align-items-end justify-content-center ">
                                <p class="mb-2 letra-login">{{Auth::guard('usuario')->user()->nombre[0]}}</p>
                            </div>
                            <div class="d-flex align-items-end justify-content-center "> 

                                <div class="a-tag d-flex flex-column mx-4">
                                    <span class="nombre">{{Auth::guard('usuario')->user()->nombre}}</span>
                                    <span class="correo">{{Auth::guard('usuario')->user()->correo}}</span>
                                </div>
                            </div>
                           <div class="d-flex align-items-end justify-content-center ">
                                <a href="{{route('usuario.logout')}}" class="a-tag logout-icon">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                </a>
                           </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-10 px-5">
            @yield('content')
        </div>
    </div>
</div>

</body>
</html>