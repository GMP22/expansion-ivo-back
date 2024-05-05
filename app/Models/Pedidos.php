<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_pedidos', 
        'id_usuario_solicitante',
        'fecha_inicial', 
        'fecha_aceptada',
        'estado', 
        'es_departamento',
    ];

    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedidos';

    public function usuario_solicitante(){
        return $this-> hasOne(Usuario::class, 'id_usuario_solicitante', 'id_usuario');
    }

    public function articulos(){
        return $this -> belongsToMany(AlmacenGeneral::class, 'articulos_por_pedido', 'id_pedido', 'id_numero_articulo', 'id_proveedor');
    }

    public function lotesAgarradosPorPedido(){
        return $this -> belongsToMany(Pedidos::class, 'lotes_solicitados', 'id_pedido_proveniente', 'id_pedido_receptor', 'id_articulo', 'id_usuario_solicitante');
    }
}
