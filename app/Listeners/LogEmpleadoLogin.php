<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\RegistroInteraccion;

class LogEmpleadoLogin
{
    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $empleado = $event->user;

        RegistroInteraccion::create([
            'empleado_id' => $empleado->id,
            'accion' => 'login',
            'modulo' => 'auth',
            'registro_id' => null,
            'descripcion' => 'Empleado inició sesión',
            'datos_anteriores' => null,
            'datos_nuevos' => null,
        ]);
    }
}
