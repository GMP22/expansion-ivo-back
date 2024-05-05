<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaArticulos extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_categoria', 
        'nombre_categoria',
    ];

    protected $table = 'categoria_articulos';
    protected $primaryKey = 'id_categoria';

    public function articulos(){
        return $this-> hasMany(AlmacenGeneral::class, 'id_categoria', 'id_categoria');
    }

}
