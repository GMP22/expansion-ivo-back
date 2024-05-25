<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cita';

    protected $fillable = [
        'sip',
        'hora',
        'id_servicio',
        'id_usuario_medico',
        'id_usuario_paciente',
        'id_usuario_radiologo',
        'id_usuario_administrativo',
        'estado',
    ];

    public function articulosEnCita(){
        return $this->belongsToMany(InventarioClinica::class, 'articulos_por_cita', 'id_cita', 'id_articulo');
    }

    public function medicos(){
        return $this->belongsTo(Medico::class);
    }

    public function pacientes(){
        return $this->belongsTo(Paciente::class);
    }

    public function radiologos(){
        return $this->belongsTo(Radiologo::class);
    }

    public function administrativos(){
        return $this->belongsTo(Administrativo::class);
    }
}
