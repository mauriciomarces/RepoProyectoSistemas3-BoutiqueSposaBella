<?php

namespace App\Observers;

use App\Models\Empleado;
use App\Models\RegistroInteraccion;
use App\Helpers\DeviceHelper;

class EmpleadoObserver
{
    /**
     * Handle the Empleado "created" event.
     */
    public function created(Empleado $empleado)
    {
        $empleado_id = session('empleado_id');
        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'create',
            'modulo' => 'empleado',
            'registro_id' => $empleado->ID_empleado,
            'descripcion' => 'Se creó un empleado',
            'datos_anteriores' => null,
            'datos_nuevos' => $empleado->toArray(),
        ]);
    }

    /**
     * Handle the Empleado "updated" event.
     */
    public function updated(Empleado $empleado)
    {
        $empleado_id = session('empleado_id');
        $originalData = $empleado->getOriginal();

        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'update',
            'modulo' => 'empleado',
            'registro_id' => $empleado->ID_empleado,
            'descripcion' => 'Se actualizó un empleado',
            'datos_anteriores' => $originalData,
            'datos_nuevos' => $empleado->toArray(),
        ]);
    }

    /**
     * Handle the Empleado "deleted" event.
     */
    public function deleted(Empleado $empleado)
    {
        $empleado_id = session('empleado_id');

        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'delete',
            'modulo' => 'empleado',
            'registro_id' => $empleado->ID_empleado,
            'descripcion' => 'Se eliminó un empleado',
            'datos_anteriores' => $empleado->toArray(),
            'datos_nuevos' => null,
        ]);
    }
}
