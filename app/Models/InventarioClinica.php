<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioClinica extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_articulo_clinica', 
        'id_articulo',
        'estado', 
        'lotes_disponibles',
        'stock_minimo',
        'pedido_automatico',
    ];

    protected $table = 'inventario_clinica';
    protected $primaryKey = 'id_articulo_clinica';

    public function articulo(){
        return $this->hasOne(AlmacenGeneral::class, 'id_articulo', 'id_articulo');
    }

    public function departamentos(){
        return $this->belongsToMany(Servicio::class, 'inventario_departamentos', 'id_departamento', 'id_articulo_clinica')
    }

    public function medicos(){
        return $this->belongsToMany(Medico::class, 'inventario_medico', 'id_usuario_medico', 'id_articulo_departamento')
    }
}