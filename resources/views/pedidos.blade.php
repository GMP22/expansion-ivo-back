@extends('layouts.plantilla')

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-12 px-5">

            <div class="row py-5">
                <div class="col-12">
                    <h1 class="title color">Pedidos</h1>
                </div>
            </div>

            <div class="row">

                <div class="col-7 d-flex mb-5 justify-content-between">
                    <div class="card" style="width: 15rem;">
                        <div class="card-body mt-3">
                            <div class="d-flex">
                                <fa-icon [icon]="faMagnifyingGlass" class="mx-2 icon-calender">
                                </fa-icon><h2>asdas</h2>
                            </div>
                            <h2 class="card-title">Card title</h2>
                        </div>
                    </div>

                    <div class="card" style="width: 15rem;">
                        <div class="card-body mt-3">
                            <div class="d-flex">
                                <fa-icon [icon]="faMagnifyingGlass" class="mx-2 icon-calender">
                                </fa-icon><h2>asdas</h2>
                            </div>
                            <h2 class="card-title">Card title</h2>
                        </div>
                    </div>

                    <div class="card" style="width: 15rem;">
                        <div class="card-body mt-3">
                            <div class="d-flex">
                                <fa-icon [icon]="faMagnifyingGlass" class="mx-2 icon-calender">
                                </fa-icon><h2>asdas</h2>
                            </div>
                            <h2 class="card-title">Card title</h2>
                        </div>
                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-6 mb-5 ms-3"> <!--Este apartado despliega los componentes que correspondan a una ruta especifica-->
                  
                    <div class="row font">
                        <div class="col-2 text-center enabled">
                                <span >Entradas</span>
                            </div>
                            <div class="col-2 text-center disabled">
                                <span >Historial</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-5 d-flex flex-row flex-row-reverse h-50">
                        <button type="button">Crear Pedido</button>
                    </div>

                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 px-5">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script type="text/javascript">
    var dtOptions = {
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                emptyTable: ''
            },
            pagingType: "numbers",
            info: false
        };
        new DataTable('#usuarios-table', dtOptions);
</script>