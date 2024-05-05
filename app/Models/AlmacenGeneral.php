<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlmacenGeneral extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_articulo', 
        'nombre',
        'id_categoria', 
        'id_fabricante',
    ];

    protected $table = 'almacen_general';
    protected $primaryKey = 'id_articulo';

    public function categoria(){
        return $this-> hasOne(CategoriaArticulo::class, 'id_categoria', 'id_categoria');
    }

    public function fabricante(){
        return $this-> hasOne(Fabricante::class, 'id_fabricante', 'id_fabricante');
    }

    public function proveedores(){
        return $this -> belongsToMany(Proveedor::class, 'articulos_por_proveedor', 'id_articulo', 'id_proveedor');
    }

    public function pedidos(){
        return $this -> belongsToMany(Pedidos::class, 'articulos_por_pedido', 'id_pedido', 'numero_articulo', 'id_proveedor');
    }

    public function articuloConPedidosAutomaticos(){
        return $this -> belongsToMany(AlmacenGeneral::class, 'articulos_automatizado', 'id_articulo', 'id_usuario', 'id_proveedor');
    }

    public function lotesAgarradosDeArticulo(){
        return $this -> belongsToMany(AlmacenGeneral::class, 'lotes_solicitados', 'id_pedido_proveniente', 'id_pedido_receptor', 'id_articulo', 'id_usuario_solicitante');
    }
}
