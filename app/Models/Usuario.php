<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{

    use Notifiable;

    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    // protected $guard = 'usuario';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dni',
        'nombre',
        'apellido1',
        'apellido2',
        'Sexo',
        'fecha_nacimiento',
        'correo',
        'codigo_postal',
        'direccion',
        'nombre_cuenta',
        'password',
        'id_rol',
        'telefono',
        'esJefe',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }


    public function gestor()
    {
        return $this->hasOne(Gestor::class, 'id_usuario_gestor');
    }

    public function administrativo()
    {
        return $this->hasOne(Administrativo::class, 'id_usuario_administrativo');
    }


    public function medico()
    {
        return $this->hasOne(Medico::class, 'id_usuario_medico');
    }


    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'id_usuario_paciente');
    }


    public function radiologo()
    {
        return $this->hasOne(Radiologo::class, 'id_usuario_radiologo');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'contraseña',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


}
