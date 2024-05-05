<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabricante extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_fabricante', 
        'nombre_categoria',
    ];

    protected $table = 'fabricante';
    protected $primaryKey = 'id_fabricante';

    public function articulos(){
        return $this-> hasMany(AlmacenGeneral::class, 'id_fabricante', 'id_fabricante');
    }
}
