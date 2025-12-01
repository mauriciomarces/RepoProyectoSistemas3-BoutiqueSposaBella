<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // para login
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    // table name en minúsculas para coincidir con la BD restaurada
    protected $table = 'empleado';
    protected $primaryKey = 'ID_empleado';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'CI',
        'puesto',
        'experiencia',
        'fecha_contratacion',
        'salario',
        'correo',
        'password',
        'ID_sucursal',
        'ID_rol',
        'ID_seccion'
    ];

    protected $hidden = [
        'password',
    ];

    protected $dates = ['deleted_at', 'fecha_contratacion'];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'ID_rol');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'ID_sucursal');
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'ID_seccion');
    }
}
