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

    Route::get('citas-pendiente-radiologo/{fecha}/{id_radiologo}', [App\Http\Controllers\Api\V1\CitaController::class, 'getCitasPendientesRadiologo']);
    Route::get('citas-realizada-radiologo/{fecha}/{id_radiologo}', [App\Http\Controllers\Api\V1\CitaController::class, 'getCitasRealizadaRadiologo']);
    Route::post('crear-prueba', [App\Http\Controllers\Api\V1\PruebaController::class, 'store']);
    Route::get('citas-generales', [App\Http\Controllers\Api\V1\CitaController::class, 'getCitasMasRecientes']);
    Route::delete('imagen/{id}', [App\Http\Controllers\Api\V1\ImagenController::class, 'eliminarImagen']);
    Route::post('actualizar-prueba/{id}', [App\Http\Controllers\Api\V1\PruebaController::class, 'actualizarPrueba']);

    Route::get('pedidos/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'mostrarPedidosSegunId']);
    Route::get('pedidos-pendientes-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'pedidosPendientesGestor']);
    Route::get('pedidos-recibidos-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'pedidosRecibidosGestor']);

    Route::get('pedidos-pendientes-medico/{id}/{estado}', [App\Http\Controllers\Api\V1\PedidosController::class, 'pedidosPendientesMedico']);
    Route::get('pedidos-recibidos-medico/{id}/{estado}', [App\Http\Controllers\Api\V1\PedidosController::class, 'pedidosRecibidosMedico']);

    Route::get('solicitudes-entrantes-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'solicitudesEntrantesGestor']);
    Route::get('solicitudes-aceptados-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'solicitudesAceptadasGestor']);
    Route::get('detalles-pedido-gestor/{id}', [App\Http\Controllers\Api\V1\PedidosController::class, 'detallesPedido']);
   
    Route::get('inventario-gestor', [App\Http\Controllers\Api\V1\InventarioClinicaController::class, 'index']);

    
});

Route::apiResource('v1/pacientes', App\Http\Controllers\Api\V1\PacienteController::class);
Route::apiResource('v1/usuarios', App\Http\Controllers\Api\V1\UsuarioController::class);
Route::apiResource('v1/gestores', App\Http\Controllers\Api\V1\GestorController::class);


