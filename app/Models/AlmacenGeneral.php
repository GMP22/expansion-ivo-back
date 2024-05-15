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
        return $this-> hasOne(CategoriaArticulos::class, 'id_categoria', 'id_categoria');
    }

    public function fabricante(){
        return $this-> hasOne(Fabricante::class, 'id_fabricante', 'id_fabricante');
    }

    public function proveedores(){
        return $this -> belongsToMany(Proveedor::class, 'articulos_por_proveedor', 'id_articulo', 'id_proveedor')->withPivot('cantidad_por_lote', 'coste_por_lote');
    }

    public function pedidos(){
        return $this -> belongsToMany(Pedidos::class, 'articulos_por_pedido', 'numero_articulo', 'id_pedido')->withPivot('id_proveedor', 'lotes_recibidos');
    }

    public function articuloConPedidosAutomaticos(){
        return $this -> belongsToMany(Usuario::class, 'pedidos_automatizados', 'id_articulo', 'id_usuario')->withPivot('id_proveedor', 'stock_a_pedir');
    }
}
