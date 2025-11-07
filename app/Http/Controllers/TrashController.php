<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function index()
    {
        $clientes = Cliente::onlyTrashed()->get();
        $empleados = Empleado::onlyTrashed()->get();
        $productos = Producto::onlyTrashed()->get();
        $proveedores = Proveedor::onlyTrashed()->get();

        return view('trash.index', compact('clientes', 'empleados', 'productos', 'proveedores'));
    }

    public function restore($type, $id)
    {
        switch ($type) {
            case 'cliente':
                Cliente::withTrashed()->find($id)->restore();
                break;
            case 'empleado':
                Empleado::withTrashed()->find($id)->restore();
                break;
            case 'producto':
                Producto::withTrashed()->find($id)->restore();
                break;
            case 'proveedor':
                Proveedor::withTrashed()->find($id)->restore();
                break;
        }

        return redirect()->route('trash.index')->with('success', 'Registro restaurado exitosamente.');
    }

    public function forceDelete($type, $id)
    {
        switch ($type) {
            case 'cliente':
                Cliente::withTrashed()->find($id)->forceDelete();
                break;
            case 'empleado':
                Empleado::withTrashed()->find($id)->forceDelete();
                break;
            case 'producto':
                Producto::withTrashed()->find($id)->forceDelete();
                break;
            case 'proveedor':
                Proveedor::withTrashed()->find($id)->forceDelete();
                break;
        }

        return redirect()->route('trash.index')->with('success', 'Registro eliminado permanentemente.');
    }
}
