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
// AGREGAR ESTA LÍNEA:
use App\Http\Controllers\ProveedorController;


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
    
    // RUTAS EMPLEADOS
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // ============================================
    // RUTAS PRODUCTOS (AGREGAR SI NO EXISTEN)
    // ============================================
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

    // ============================================
    // RUTAS PROVEEDORES (NUEVAS - AGREGAR ESTAS)
    // ============================================
    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
    Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::get('/proveedores/{id}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit');
    Route::put('/proveedores/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');

});
