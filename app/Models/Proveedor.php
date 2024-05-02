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
        'nombre',
        'email', 
        'nombre',
        'codigo_postal', 
        'direccion',
        'provincia', 
        'nombre',
        'ciudad', 
        'cod_trasmision',
    ];

    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';
    public $incrementing = false;

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_gestor_logistico', 'id_usuario');
    }
}
