<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_pedido', 
        'id_usuario_solicitante',
        'fecha_inicial', 
        'fecha_aceptada',
        'estado', 
        'es_departamento',
        'id_servicio',
    ];

    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';

    public function usuario(){
        return $this-> belongsTo(Usuario::class, 'id_usuario_solicitante', 'id_usuario');
    }

    public function articulos(){
        //return $this -> belongsToMany(AlmacenGeneral::class, 'articulos_por_pedido', 'id_pedido', 'id_numero_articulo', 'id_proveedor');
        return $this -> belongsToMany(AlmacenGeneral::class, 'articulos_por_pedido', 'id_pedido', 'numero_articulo')->withPivot('lotes_recibidos', 'id_proveedor');
    }

    public function proveedores(){
        //return $this -> belongsToMany(AlmacenGeneral::class, 'articulos_por_pedido', 'id_pedido', 'id_numero_articulo', 'id_proveedor');
        return $this -> belongsToMany(Proveedor::class, 'articulos_por_pedido', 'id_pedido', 'id_proveedor') ->withPivot('lotes_recibidos');
    }

    public function servicio(){
        return $this -> hasOne(Servicio::class, 'id_servicio', 'id_servicio');
    }
}
