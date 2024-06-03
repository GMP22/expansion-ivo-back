<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\AuthFrontController;
use App\Http\Controllers\Api\V1\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [LoginController::class, 'store']);
});*/

Route::group([

    'middleware' => 'api',
    'prefix' => 'v1'

], function ($router) {

    Route::post('login', [AuthFrontController::class, 'login']);
    /*Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);*/

    Route::apiResource('pacientes', App\Http\Controllers\Api\V1\PacienteController::class);
    Route::apiResource('citas', App\Http\Controllers\Api\V1\CitaController::class);

    

    Route::post('crear-citas', [App\Http\Controllers\Api\V1\CitaController::class, 'store']);
    Route::post('alta-paciente', [App\Http\Controllers\Api\V1\UsuarioController::class, 'store']);

    Route::post('registrar-paciente', [App\Http\Controllers\Api\V1\PacienteController::class, 'store']);

    Route::post('registrar-diagnostico', [App\Http\Controllers\Api\V1\DiagnosticoController::class, 'store']);
    Route::get('obtener-diagnostico/{id_cita}', [App\Http\Controllers\Api\V1\DiagnosticoController::class, 'mostrarDiagnostico']);
    Route::post('modificar-diagnostico/{id}', [App\Http\Controllers\Api\V1\DiagnosticoController::class, 'update']);

    Route::get('mostrar-volante/{id}', [App\Http\Controllers\Api\V1\DiagnosticoController::class, 'mostrarVolante']);
    Route::post('modificar-volante/{id}', [App\Http\Controllers\Api\V1\DiagnosticoController::class, 'actualizarVolante']);
    
    Route::apiResource('medicos', App\Http\Controllers\Api\V1\MedicoController::class);
    Route::get('medicos/{id_usuario_medico}', [App\Http\Controllers\Api\V1\MedicoController::class, 'listarPorId']);

    Route::apiResource('servicios', App\Http\Controllers\Api\V1\ServicioController::class);
    Route::delete('/api/v1/citas/{cita}', [App\Http\Controllers\Api\V1\CitaController::class, 'destroy']);
    //Route::apiResource('usuarios', App\Http\Controllers\Api\V1\UsuarioController::class);
    Route::put('usuarios/{usuario}', [App\Http\Controllers\Api\V1\UsuarioController::class, 'update']);


    Route::put('citas/{cita}', [App\Http\Controllers\Api\V1\CitaController::class, 'update']);
    Route::get('citas/{fecha}', [App\Http\Controllers\Api\V1\CitaController::class, 'getCitasMedico']);

    Route::put('usuarios/{cita}', [App\Http\Controllers\Api\V1\CitaController::class, 'update']);
   // Route::get('citas/{fecha}', [App\Http\Controllers\Api\V1\CitaController::class, 'getCitasMedico']);

    Route::get('citas-pendiente-medico/{fecha}/{id_medico}', [App\Http\Controllers\Api\V1\CitaController::class, 'getCitasPendientesMedico']);
    Route::get('citas-realizada-medico/{fecha}/{id_medico}', [App\Http\Controllers\Api\V1\CitaController::class, 'getCitasRealizadasMedico']);

    Route::get('prueba/{id}', [App\Http\Controllers\Api\V1\PruebaController::class, 'getPrueba']);
    Route::get('pruebas-paciente/{id}', [App\Http\Controllers\Api\V1\PruebaController::class, 'getAllPruebasPaciente']);
    Route::get('informacion-prueba/{id}', [App\Http\Controllers\Api\V1\PruebaController::class, 'getPruebaByPruebaId']);
    
    Route::get('obtener-personal/{id_rol}', [App\Http\Controllers\Api\V1\UsuarioController::class, 'show']);

    Route::post('registrar-articulos-en-cita/{id_cita}', [App\Http\Controllers\Api\V1\DiagnosticoController::class, 'registrarArticulosCita']);
    Route::post('modificar-articulos-en-cita/{id_cita}', [App\Http\Controllers\Api\V1\DiagnosticoController::class, 'modificarArticulosCita']);
    Route::get('inventario-medico-cita/{id_cita}', [App\Http\Controllers\Api\V1\DiagnosticoController::class, 'inventarioMedicoCita']);

    Route::get('citas-pendiente-radiologo/{fecha}/{id_radiologo}', [App\Http\Controllers\Api\V1\CitaController::class, 'getCitasPendientesRadiologo']);
    Route::get('citas-realizada-radiologo/{fecha}/{id_radiologo}', [App\Http\Controllers\Api\V1\CitaController::class, 'getCitasRealizadaRadiologo']);
    Route::post('crear-prueba', [App\Http\Controllers\Api\V1\PruebaController::class, 'store']);
    Route::get('citas-generales', [App\Http\Controllers\Api\V1\CitaController::class, 'getCitasMasRecientes']);
    Route::delete('imagen/{id}', [App\Http\Controllers\Api\V1\ImagenController::class, 'eliminarImagen']);
    Route::post('actualizar-prueba/{id}', [App\Http\Controllers\Api\V1\PruebaController::class, 'actualizarPrueba']);

    Route::get('pedido', [App\Http\Controllers\Api\V1\PedidosController::class, 'index']);
    Route::get('pedidos/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'mostrarPedidosSegunId']);
    Route::get('pedidos-pendientes-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'pedidosPendientesGestor']);

    Route::get('pedidos-pendientes-medico/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'pedidosPendientesMedico']);
    Route::get('pedidos-recibidos-medico/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'pedidosRecibidosMedico']);

    Route::get('pedidos-recibidos-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'pedidosRecibidosGestor']);

    Route::get('pedidos-pendientes-medico/{id}/{estado}', [App\Http\Controllers\Api\V1\PedidosController::class, 'pedidosPendientesMedico']);
    Route::get('pedidos-recibidos-medico/{id}/{estado}', [App\Http\Controllers\Api\V1\PedidosController::class, 'pedidosRecibidosMedico']);

    Route::get('solicitudes-entrantes-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'solicitudesEntrantesGestor']);
    Route::get('solicitudes-aceptados-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'solicitudesAceptadasGestor']);
    
    Route::get('detalles-solicitudes-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'detallesSolicitudGestor']);
    Route::get('articulos-solicitudes-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'articulosSolicitud']);

    Route::get('articulos-minimos-solicitud-gestor/{id}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'articulosMinimosSolicitud']);

    Route::get('detalles-pedido-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'detallesPedido']);
   
    Route::get('inventario-gestor', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'index']);

    Route::get('inventario-medico/{id}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'indexMedico']);
    Route::get('detalles-articulos-medico/{idUsuario}/{idArticulo}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'detallesArticuloMedico']);
    Route::get('pedidos-articulo-especifico-medico/{idUsuario}/{idArticulo}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'pedidosConArticuloEspecificoMedico']);
    Route::post('nueva-funcion-automatica-medico', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'nuevaFunctionAutomaticaMedico']);
    Route::post('eliminar-funcion-automatica-medico', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'eliminarFuncionAutomaticaMedico']);
    Route::post('cambiar-minimo-medico/{idUsuario}/{idArticulo}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'cambiarMinimosMedico']);

    Route::get('numeros-minimos-gestor', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'numeroMinimosGestor']);
    Route::get('articulos-minimos-gestor', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'articulosMinimosGestor']);
    Route::get('numeros-automaticos-gestor', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'numeroArticulosAutomaticos']);
    Route::get('articulos-automaticos-gestor', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'articulosAutomaticos']);
    Route::get('detalles-articulos-gestor/{id}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'detallesArticuloGestor']);
    Route::get('pedidos-articulo-especifico-gestor/{id}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'pedidosConArticuloEspecifico']);
    Route::get('detalles-pedido-especifico-gestor/{id}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'detallesPedidoEspecifico']);
    
    Route::post('nueva-funcion-automatica', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'nuevaFunctionAutomatica']);
    Route::post('eliminar-funcion-automatica', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'eliminarFuncionAutomatica']);
    
    Route::post('cambiar-minimo-gestor/{id}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'cambiarMinimos']);
    
    Route::get('articulos-crear-pedido', [App\Http\Controllers\Api\V1\AlmacenGeneralController::class, 'articulosCrearPedido']);
    Route::get('articulos-crear-pedido-medico/{id}', [App\Http\Controllers\Api\V1\AlmacenGeneralController::class, 'articulosCrearPedidoMedico']);
    Route::get('articulos-minimos-crear-pedido-medico/{id}', [App\Http\Controllers\Api\V1\AlmacenGeneralController::class, 'articulosMinimosCrearPedidoMedico']);
    Route::get('articulos-lotes-crear-pedido-medico/{id}/{idArticulo}', [App\Http\Controllers\Api\V1\AlmacenGeneralController::class, 'numeroLotesCrearPedidoMedico']);
    Route::get('articulos-lotes-crear-pedido-gestor/{idArticulo}', [App\Http\Controllers\Api\V1\AlmacenGeneralController::class, 'numeroLotesCrearPedidoGestor']);

    Route::get('cuadros-informativos-pedidos-medico/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'cuadrosInformativosPedidosMedico']);
    Route::get('cuadros-informativos-inventario-medico/{id}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'cuadrosInformativosInventarioMedico']);

    Route::get('articulos-minimos-crear-pedido-gestor', [App\Http\Controllers\Api\V1\AlmacenGeneralController::class, 'articulosMinimosCrearPedidoGestor']);

    Route::get('cuadros-informativos-pedidos-gestor', [App\Http\Controllers\Api\V1\PedidosController::class, 'cuadrosInformativosPedidosGestor']);
    Route::get('cuadros-informativos-inventario-gestor', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'cuadrosInformativosInventarioGestor']);

    Route::get('inventario-minimos-medico/{id}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'articulosMinimosMedico']);
    Route::get('inventario-automatico-medico/{id}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'articulosAutomaticosMedico']);

    Route::get('detalles-pedido-automatico-gestor/{id}/{idArticulo}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'detallesPedidoAutomaticoGestor']);

    Route::get('inventario-minimos-gestor', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'articulosMinimosListadoGestor']);
    Route::get('inventario-automatico-gestor', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'articulosAutomaticosGestor']);

    Route::get('solicitud-articulo-especifico/{id}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'solicitudesArticuloEspecifico']);

    Route::get('detalles-pedido-automatico/{idUsuario}/{idArticulo}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'detallesPedidoAutomatico']);
    Route::get('detalles-pedido-medico/{idPedido}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'detallesPedidoMedido']);
    Route::get('articulos-pedido-medico/{idPedido}', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'articulosPedidoMedico']);

    Route::get('articulo-usado-cita/{idMedico}/{idArticulo}', [App\Http\Controllers\Api\V1\CitaController::class, 'articuloUsadoPorCita']);
    Route::get('buscar-cita-id/{idCita}', [App\Http\Controllers\Api\V1\CitaController::class, 'buscarCitaId']);

    Route::get('detalles-articulo-crear-pedido/{id}', [App\Http\Controllers\Api\V1\AlmacenGeneralController::class, 'detallesArticulosCrearPedido']);
    Route::get('detalles-articulo-segun-proveedor/{idArticulo}/{idProveedor}', [App\Http\Controllers\Api\V1\AlmacenGeneralController::class, 'detallesArticulosSegunProveedor']);
    Route::get('articulo-segun-proveedor/{idArticulo}', [App\Http\Controllers\Api\V1\AlmacenGeneralController::class, 'proveedoresSegunArticulo']);
    Route::post('registrar-pedido-gestor/{idArticulo}', [App\Http\Controllers\Api\V1\PedidosController::class,'registrarPedidosGestor']);
    Route::post('registrar-pedido-medico/{idMedico}', [App\Http\Controllers\Api\V1\PedidosController::class,'registrarPedidosMedico']);
    Route::get('recibir-pedido-gestor/{idArticulo}', [App\Http\Controllers\Api\V1\PedidosController::class,'recibirPedidoGestor']);
    Route::post('aceptar-solicitud-gestor/{idUsuario}/{idPedido}', [App\Http\Controllers\Api\V1\PedidosController::class,'aceptarSolicitudes']);

    Route::get('proveedores', [App\Http\Controllers\Api\V1\ProveedoresController::class,'index']);
    Route::get('proveedores-numeros', [App\Http\Controllers\Api\V1\ProveedoresController::class,'numeroProveedores']);
    Route::get('proveedor-especifico/{id}', [App\Http\Controllers\Api\V1\ProveedoresController::class,'proveedorSegunId']);
    Route::get('proveedores-modal', [App\Http\Controllers\Api\V1\ProveedoresController::class,'modalProveedores']);
    Route::get('proveedores-pedidos-pendientes/{id}', [App\Http\Controllers\Api\V1\ProveedoresController::class,'pedidosPendientesPorProveedor']);
    Route::get('proveedores-pedidos-recibidos/{id}', [App\Http\Controllers\Api\V1\ProveedoresController::class,'pedidosRecibidosPorProveedor']);
    Route::post('registrar-proveedor', [App\Http\Controllers\Api\V1\ProveedoresController::class,'registrarProveedor']);
    Route::post('modificar-proveedor/{id}', [App\Http\Controllers\Api\V1\ProveedoresController::class,'modificarProveedor']);
});

Route::apiResource('v1/pacientes', App\Http\Controllers\Api\V1\PacienteController::class);
Route::apiResource('v1/usuarios', App\Http\Controllers\Api\V1\UsuarioController::class);
Route::apiResource('v1/gestores', App\Http\Controllers\Api\V1\GestorController::class);


