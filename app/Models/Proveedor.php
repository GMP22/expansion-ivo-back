<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_proveedor', 
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
        return $this -> belongsToMany(AlmacenGeneral::class, 'articulos_por_proveedor', 'id_articulo', 'id_proveedor');
    }

    public function proveedorConPedidosAutomaticos(){
        return $this -> belongsToMany(Proveedor::class, 'articulos_automatizado', 'id_articulo', 'id_usuario', 'id_proveedor');
    }
    
}
