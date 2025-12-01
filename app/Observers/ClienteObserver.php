<?php

namespace App\Observers;

use App\Models\Cliente;
use App\Models\RegistroInteraccion;
use App\Helpers\DeviceHelper;

class ClienteObserver
{
    /**
     * Handle the Cliente "created" event.
     */
    public function created(Cliente $cliente)
    {
        $empleado_id = session('empleado_id');
        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'create',
            'modulo' => 'cliente',
            'registro_id' => $cliente->ID_cliente,
            'descripcion' => 'Se creó un cliente',
            'datos_anteriores' => null,
            'datos_nuevos' => $cliente->toArray(),
        ]);
    }

    /**
     * Handle the Cliente "updated" event.
     */
    public function updated(Cliente $cliente)
    {
        $empleado_id = session('empleado_id');
        $originalData = $cliente->getOriginal();

        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'update',
            'modulo' => 'cliente',
            'registro_id' => $cliente->ID_cliente,
            'descripcion' => 'Se actualizó un cliente',
            'datos_anteriores' => $originalData,
            'datos_nuevos' => $cliente->toArray(),
        ]);
    }

    /**
     * Handle the Cliente "deleted" event.
     */
    public function deleted(Cliente $cliente)
    {
        $empleado_id = session('empleado_id');

        RegistroInteraccion::create([
            'empleado_id' => $empleado_id,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'delete',
            'modulo' => 'cliente',
            'registro_id' => $cliente->ID_cliente,
            'descripcion' => 'Se eliminó un cliente',
            'datos_anteriores' => $cliente->toArray(),
            'datos_nuevos' => null,
        ]);
    }
}
