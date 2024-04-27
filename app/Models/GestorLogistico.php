<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestorLogistico extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_usuario_gestor_logistico', 
        'id_usuario_gestor',
    ];

    protected $table = 'gestor_logistico';
    protected $primaryKey = 'id_usuario_gestor_logistico';
    public $incrementing = false;

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_gestor_logistico', 'id_usuario');
    }
}
