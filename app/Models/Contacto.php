<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $table = 'contactos';

    protected $fillable = [
        'paciente_id',
        'nombre',
        'apellido',
        'telefono',
        'relacion',
        'direccion',
        'disponibilidad',
        'tiene_llaves',
        'nivel_prioridad'
    ];

    protected $casts = [
        'tiene_llaves' => 'boolean',
        'nivel_prioridad' => 'integer'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
