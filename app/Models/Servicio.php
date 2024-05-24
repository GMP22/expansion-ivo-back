<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_servicio';
    protected $fillable = [
        'nombre_servicio',
        'jefe_departamento',
        'fecha_creacion',
        'id_usuario_gestor',
    ];
    public function gestores()
    {
        return $this->belongsToMany(Gestor::class, 'gestor_servicios', 'id_servicio', 'id_usuario_gestor');
    }
    public function medicos()
    {
        return $this->hasMany(Medico::class, 'id_servicio');
    }
    public function radiologos()
    {
        return $this->hasMany(Radiologo::class, 'id_servicio');
    }
    public function articulosDepartamento(){
        return $this->belongsToMany(InventarioClinica::class, 'inventario_departamentos', 'id_departamento', 'id_articulo_clinica');
    }
}
