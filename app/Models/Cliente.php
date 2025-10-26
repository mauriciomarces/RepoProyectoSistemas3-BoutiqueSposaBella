<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'ID_cliente';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'direccion',
        'telefono',
        'CI',
        'ID_sucursal'
    ];
}
