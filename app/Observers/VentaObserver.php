<?php

namespace App\Observers;

use App\Models\Venta;
use App\Models\RegistroInteraccion;
use App\Helpers\DeviceHelper;

class VentaObserver
{
    /**
     * Handle the Venta "created" event.
     */
    public function created(Venta $venta)
    {
        $empleado_id = session('empleado_id');
        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'create',
            'modulo' => 'venta',
            'registro_id' => $venta->ID_venta,
            'descripcion' => 'Se creó una venta',
            'datos_anteriores' => null,
            'datos_nuevos' => $venta->toArray(),
        ]);
    }

    /**
     * Handle the Venta "updated" event.
     */
    public function updated(Venta $venta)
    {
        $empleado_id = session('empleado_id');

        // Get original data before update
        $originalData = $venta->getOriginal();

        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'update',
            'modulo' => 'venta',
            'registro_id' => $venta->ID_venta,
            'descripcion' => 'Se actualizó una venta',
            'datos_anteriores' => $originalData,
            'datos_nuevos' => $venta->toArray(),
        ]);
    }

    /**
     * Handle the Venta "deleted" event.
     */
    public function deleted(Venta $venta)
    {
        $empleado_id = session('empleado_id');

        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'delete',
            'modulo' => 'venta',
            'registro_id' => $venta->ID_venta,
            'descripcion' => 'Se eliminó una venta',
            'datos_anteriores' => $venta->toArray(),
            'datos_nuevos' => null,
        ]);
    }
}
