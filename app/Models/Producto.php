<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';
    protected $primaryKey = 'ID_producto';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'descripcion_corta',
        'categoria',
        'imagen',
        'stock',
        'stock_minimo',
        'ID_proveedor',
        'precio'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'ID_proveedor');
    }

    // MÉTODOS PARA EL CONTROL DE INVENTARIO
    public function necesitaReposicion()
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function estaAgotado()
    {
        return $this->stock == 0;
    }

    public function getEstadoStockAttribute()
    {
        if ($this->estaAgotado()) {
            return 'agotado';
        } elseif ($this->necesitaReposicion()) {
            return 'bajo';
        } else {
            return 'normal';
        }
    }
}