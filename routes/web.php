<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GestorController;
use App\Http\Controllers\JefeDepartamentoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\RolController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/hola', function () {
//     return 'Hola mundo';
// });



Route::get('/', [UsuarioController::class, 'Index'])->name('login_form');
Route::post('/login/owner', [UsuarioController::class, 'Login'])->name('usuario.login');



Route::group(['middleware' => 'gestor'], function(){

    Route::get('/logout', [UsuarioController::class, 'logout'])->name('usuario.logout');

    //Routas Usuario
    Route::get('/usuario', [GestorController::class, 'Dashboard'])->name('gestor.usuario');
    Route::get('/usuario/add-usuario', [UsuarioController::class, 'addUsuario'])->name('gestor.add-usuario');
    Route::post('/usuario/add-usuario/anyadir', [UsuarioController::class, 'store'])->name('usuario.crear');
    Route::get('/usuario/{id}/editar', [UsuarioController::class, 'edit'])->name('usuario.edit');
    Route::post('/usuario/{id}/editar/modificar', [UsuarioController::class, 'update'])->name('usuario.modificar');
    Route::get('/usuario/{id}/perfil', [UsuarioController::class, 'perfil'])->name('usuario.perfil');
    Route::delete('/usuario/{id}', [UsuarioController::class, 'destroy'])->name('usuario.destroy');

    //Routas Servicio
    Route::get('/servicio', [ServicioController::class, 'index'])->name('gestor.servicio');
    Route::get('/servicio/add-servicio', [ServicioController::class, 'addServicio'])->name('gestor.add-servicio');
    Route::post('/servicio/add-servicio/anyadir', [ServicioController::class, 'store'])->name('servicio.crear');
    Route::post('/servicio/{id}/editar/modificar', [ServicioController::class, 'update'])->name('servicio.modificar');
    Route::get('/servicio/{id}/editar', [ServicioController::class, 'edit'])->name('servicio.edit');


    //Routas Rol
    Route::get('/rol', [RolController::class, 'index'])->name('gestor.rol');
    Route::get('/rol/add-rol', [RolController::class, 'addRol'])->name('gestor.add-rol');
    Route::post('/rol/add-rol/anyadir', [RolController::class, 'store'])->name('rol.crear');
    Route::post('/rol/{id}/editar/modificar', [RolController::class, 'update'])->name('rol.modificar');
    Route::get('/rol/{id}/editar', [RolController::class, 'edit'])->name('rol.edit');

});

Route::group(['middleware' => 'jefe'], function(){
    Route::get('/solicitudes/{id}', [JefeDepartamentoController::class, 'plantilla'])->name('solicitudes');
    Route::get('/solicitudes/{id}', [JefeDepartamentoController::class, 'solicitudes'])->name('solicitudes');
    Route::get('/solicitudes/{id}/{idSolicitud}', [JefeDepartamentoController::class, 'detallesSolicitud'])->name('solicitudes.detalles');
    Route::get('/solicitudes/{id}/{idSolicitud}/comprobar-minimos', [JefeDepartamentoController::class, 'verificarMinimosArticulos'])->name('solicitudes.minimos');
    Route::post('/solicitudes/{id}/{idSolicitud}/aceptar-solicitud', [JefeDepartamentoController::class, 'aceptarSolicitud'])->name('solicitudes.aceptar');
    Route::get('/pedidos/{id}', [JefeDepartamentoController::class, 'pedidos'])->name('pedidos');
    Route::get('/pedidos/{id}/crear-pedido', [JefeDepartamentoController::class, 'crearPedidos'])->name('pedidos.crear');
    Route::get('/pedidos/{id}/{idPedido}', [JefeDepartamentoController::class, 'detallesPedido'])->name('pedidos.detalles');
    Route::post('/pedidos/subir-pedido/{id}', [JefeDepartamentoController::class, 'subirPedido'])->name('pedidos.subir');
    Route::get('/inventario/{id}', [JefeDepartamentoController::class, 'inventario'])->name('inventario');
    Route::get('/inventario/{id}/{idArticulo}', [JefeDepartamentoController::class, 'detalleArticulo'])->name('inventario.detalles');
    Route::post('/inventario/{id}/{idArticulo}/cambiar-minimo', [JefeDepartamentoController::class, 'cambiarMinimos'])->name('inventario.cminimo');
    Route::post('/inventario/{id}/{idArticulo}/pedido-automatico', [JefeDepartamentoController::class, 'funcionAutomatica'])->name('inventario.cAuto');
});







// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';
