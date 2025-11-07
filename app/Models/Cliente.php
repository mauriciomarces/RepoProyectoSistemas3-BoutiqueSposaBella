<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

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
        'ID_sucursal',
        'busto',
        'cintura',
        'cadera'
    ];

    protected $hidden = [
        'password',
    ];

    protected $dates = ['deleted_at'];
}
