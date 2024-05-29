<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_usuario_medico', 
        'id_usuario_gestor',
        'id_servicio',
    ];
    protected $table = 'medicos';
    protected $primaryKey = 'id_usuario_medico';
    public $incrementing = false;
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_medico', 'id_usuario');
    }

    public function gestor()
    {
        return $this->belongsTo(Gestor::class, 'id_usuario_gestor', 'id_usuario_gestor');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id_servicio');
    }
    public function articulosMedicos(){
        return $this->belongsToMany(Medico::class, 'inventario_medico', 'id_usuario_medico', 'id_usuario_medico') ->withPivot('estado','lotes_disponibles','stock_minimo','pedido_automatico');
    }
}
