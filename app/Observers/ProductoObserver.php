<?php

namespace App\Observers;

use App\Models\Producto;
use App\Models\RegistroInteraccion;
use App\Helpers\DeviceHelper;

class ProductoObserver
{
    /**
     * Handle the Producto "created" event.
     */
    public function created(Producto $producto)
    {
        $empleado_id = session('empleado_id');
        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'create',
            'modulo' => 'producto',
            'registro_id' => $producto->ID_producto,
            'descripcion' => 'Se creó un producto',
            'datos_anteriores' => null,
            'datos_nuevos' => $producto->toArray(),
        ]);
    }

    /**
     * Handle the Producto "updated" event.
     */
    public function updated(Producto $producto)
    {
        $empleado_id = session('empleado_id');
        $originalData = $producto->getOriginal();

        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'update',
            'modulo' => 'producto',
            'registro_id' => $producto->ID_producto,
            'descripcion' => 'Se actualizó un producto',
            'datos_anteriores' => $originalData,
            'datos_nuevos' => $producto->toArray(),
        ]);
    }

    /**
     * Handle the Producto "deleted" event.
     */
    public function deleted(Producto $producto)
    {
        $empleado_id = session('empleado_id');

        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'delete',
            'modulo' => 'producto',
            'registro_id' => $producto->ID_producto,
            'descripcion' => 'Se eliminó un producto',
            'datos_anteriores' => $producto->toArray(),
            'datos_nuevos' => null,
        ]);
    }
}
