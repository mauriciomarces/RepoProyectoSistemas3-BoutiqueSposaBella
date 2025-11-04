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
        'imagen_blob',
        'imagen_mime',
        'stock',
        'stock_minimo',
        'ID_proveedor',
        'precio'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'ID_proveedor');
    }

    // Devuelve la imagen en formato data-uri si hay blob, si no intenta usar el nombre de archivo
    public function getImagenDataAttribute()
    {
        if (!empty($this->imagen_blob) && !empty($this->imagen_mime)) {
            return 'data:' . $this->imagen_mime . ';base64,' . base64_encode($this->imagen_blob);
        }

        if (!empty($this->imagen) && file_exists(public_path('images/productos/' . $this->imagen))) {
            return asset('images/productos/' . $this->imagen);
        }

        return asset('images/productos/default.png');
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