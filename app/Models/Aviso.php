<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'fecha_hora',
        'descripcion',
        'completado',
        'fecha_completado',
        'categoria_id',
        'paciente_id',
        'operador_id'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'completado' => 'boolean',
        'fecha_completado' => 'datetime'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id');
    }
}
