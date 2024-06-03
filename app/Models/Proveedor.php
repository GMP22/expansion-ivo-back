<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_proveedor', 
        'nif',
        'nombre',
        'telefono', 
        'email', 
        'codigo_postal', 
        'direccion',
        'provincia', 
        'ciudad', 
        'cod_trasmision',
    ];

    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';

    public function articulos(){
        return $this -> belongsToMany(AlmacenGeneral::class, 'articulos_por_proveedor',  'id_proveedor', 'id_articulo')->withPivot('cantidad_por_lote', 'coste_por_lote');
    }

    public function pedidos(){
        //return $this -> belongsToMany(AlmacenGeneral::class, 'articulos_por_pedido', 'id_pedido', 'id_numero_articulo', 'id_proveedor');
        return $this -> belongsToMany(Pedidos::class, 'articulos_por_pedido', 'id_proveedor', 'id_pedido') ->withPivot('lotes_recibidos');
    }

    public function proveedorConPedidosAutomaticos(){
        return $this -> belongsToMany(Proveedor::class, 'articulos_automatizado', 'id_articulo', 'id_usuario', 'id_proveedor');
    }
    
}
