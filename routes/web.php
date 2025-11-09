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
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\FleteController;
use App\Http\Controllers\MovimientoFinancieroController;
use App\Http\Controllers\AnalisisFinancieroController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\TrashController;


// ============================================
// RUTAS PÚBLICAS (SIN AUTENTICACIÓN)
// ============================================

// Página principal
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Catálogo público
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');

// ============================================
// LOGIN DE EMPLEADOS (SISTEMA ADMINISTRATIVO)
// ============================================
Route::get('/empleado/login', [AuthController::class, 'showLoginEmpleado'])->name('empleado.login');
Route::post('/empleado/login', [AuthController::class, 'loginEmpleado'])->name('empleado.login.post');

// ============================================
// RUTAS PROTEGIDAS (SOLO EMPLEADOS AUTENTICADOS)
// Requiere: Admin, Vendedora o Costurera
// ============================================
Route::middleware(['check.employee'])->group(function () {
    // Logout de empleado (protegido)
    Route::post('/empleado/logout', [AuthController::class, 'logoutEmpleado'])->name('empleado.logout');

    // ============================================
    // CLIENTES
    // ============================================
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    
    // ============================================
    // EMPLEADOS (SOLO ADMINISTRADORES)
    // ============================================
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    Route::get('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create');
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
    Route::get('/empleados/{id}/edit', [EmpleadoController::class, 'edit'])->name('empleados.edit');
    Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');
    Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

    // ============================================
    // PRODUCTOS
    // ============================================
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

    // ============================================
    // PROVEEDORES
    // ============================================
    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
    Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::get('/proveedores/{id}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit');
    Route::put('/proveedores/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');

    // ============================================
    // CATEGORÍAS (Si las usas)
    // ============================================
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{id}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    // ============================================
    // MOVIMIENTOS FINANCIEROS
    // ============================================
    Route::get('/movimientos', [MovimientoFinancieroController::class, 'index'])->name('movimientos.index');
    Route::get('/movimientos/create', [MovimientoFinancieroController::class, 'create'])->name('movimientos.create');
    Route::post('/movimientos', [MovimientoFinancieroController::class, 'store'])->name('movimientos.store');
    Route::get('/movimientos/report', [MovimientoFinancieroController::class, 'generateReport'])->name('movimientos.report');
    Route::get('/movimientos/export/pdf', [MovimientoFinancieroController::class, 'exportPdf'])->name('movimientos.export.pdf');
    Route::get('/movimientos/print', [MovimientoFinancieroController::class, 'printReport'])->name('movimientos.print');
    Route::get('/movimientos/latest', [MovimientoFinancieroController::class, 'latest'])->name('movimientos.latest');
    Route::get('/movimientos/export/csv', [MovimientoFinancieroController::class, 'exportCsv'])->name('movimientos.export.csv');
    
    // ============================================
    // ANÁLISIS FINANCIERO
    // ============================================
    Route::get('/analisis-financiero', [AnalisisFinancieroController::class, 'index'])->name('analisis.index');
    Route::get('/analisis-financiero/reporte-mensual', [AnalisisFinancieroController::class, 'reporteMensual'])->name('analisis.reporte-mensual');
    Route::get('/analisis-financiero/graficos', [AnalisisFinancieroController::class, 'graficosTendencias'])->name('analisis.graficos');
    // Reportes combinados / Confecciones module
    Route::get('/confecciones', [\App\Http\Controllers\ConfeccionController::class, 'index'])->name('confecciones.index');
    Route::get('/confecciones/create', [\App\Http\Controllers\ConfeccionController::class, 'create'])->name('confecciones.create');
    Route::post('/confecciones', [\App\Http\Controllers\ConfeccionController::class, 'store'])->name('confecciones.store');

    // ============================================
    // FLETES (Si los usas)
    // ============================================
    Route::get('/fletes', [FleteController::class, 'index'])->name('fletes.index');
    Route::get('/fletes/create', [FleteController::class, 'create'])->name('fletes.create');
    Route::post('/fletes', [FleteController::class, 'store'])->name('fletes.store');
    Route::get('/fletes/{id}/edit', [FleteController::class, 'edit'])->name('fletes.edit');
    Route::put('/fletes/{id}', [FleteController::class, 'update'])->name('fletes.update');
    Route::delete('/fletes/{id}', [FleteController::class, 'destroy'])->name('fletes.destroy');

    // ============================================
    // PAPELERA DE RECICLAJE
    // ============================================
    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::patch('/trash/{type}/{id}/restore', [TrashController::class, 'restore'])->name('trash.restore');
    Route::delete('/trash/{type}/{id}/force-delete', [TrashController::class, 'forceDelete'])->name('trash.force-delete');
});

// ============================================
// RUTAS REMOVIDAS (LOGIN Y REGISTRO DE CLIENTES)
// Las siguientes rutas fueron ELIMINADAS porque
// solo se permite acceso a empleados
// ============================================
// Route::get('/login', ...)
// Route::post('/login', ...)
// Route::get('/logout', ...)
// Route::get('/registro', ...)
// Route::post('/registro', ...)