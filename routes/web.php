<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RegistroController;

// RUTA PRINCIPAL (WELCOME)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// RUTAS CATÁLOGO (PÚBLICO)
Route::get('/catalogo', [CatalogoController::class, 'index']);

// ============================================
// LOGIN Y REGISTRO DE CLIENTES (PÚBLICO)
// ============================================
Route::get('/login', [AuthController::class, 'showLoginCliente'])->name('login');
Route::post('/login', [AuthController::class, 'loginCliente'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logoutCliente'])->name('logout');

Route::get('/registro', [RegistroController::class, 'mostrarFormulario'])->name('registro');
Route::post('/registro', [RegistroController::class, 'registrar'])->name('registro.guardar');

// ============================================
// LOGIN DE EMPLEADOS (SISTEMA ADMINISTRATIVO)
// ============================================
Route::get('/empleado/login', [AuthController::class, 'showLoginEmpleado'])->name('empleado.login');
Route::post('/empleado/login', [AuthController::class, 'loginEmpleado'])->name('empleado.login.post');
Route::post('/empleado/logout', [AuthController::class, 'logoutEmpleado'])->name('empleado.logout');

// ============================================
// RUTAS PROTEGIDAS (SOLO EMPLEADOS)
// Requiere: Admin, Vendedora o Costurera
// ============================================
Route::middleware(['check.employee'])->group(function () {
    
    // RUTAS CLIENTES
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    
    // RUTAS CATEGORÍAS
    Route::resource('categorias', CategoriaController::class);
    
    // RUTAS PRODUCTOS
    Route::resource('productos', ProductoController::class);
    Route::get('/productos/stock-bajo', [ProductoController::class, 'bajoStock'])->name('productos.bajoStock');
    
    // RUTAS MOVIMIENTOS
    Route::resource('movimientos', MovimientoController::class);
    
    // RUTAS EMPLEADOS
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    
});