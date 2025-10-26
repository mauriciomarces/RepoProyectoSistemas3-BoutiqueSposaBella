<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // para login
use Illuminate\Notifications\Notifiable;

class Empleado extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'EMPLEADO';
    protected $primaryKey = 'ID_empleado';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'correo', 'password', 'puesto', 'ID_rol', 'ID_sucursal', 'ID_seccion'
    ];

    protected $hidden = [
        'password',
    ];
}
