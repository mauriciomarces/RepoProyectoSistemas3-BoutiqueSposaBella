<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // para login
use Illuminate\Notifications\Notifiable;

class Empleado extends Authenticatable
{
    use HasFactory, Notifiable;

    // table name en minúsculas para coincidir con la BD restaurada
    protected $table = 'empleado';
    protected $primaryKey = 'ID_empleado';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'correo', 'password', 'puesto', 'ID_rol', 'ID_sucursal', 'ID_seccion'
    ];

    protected $hidden = [
        'password',
    ];
}
