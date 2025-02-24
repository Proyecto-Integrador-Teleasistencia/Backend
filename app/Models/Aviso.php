<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 * required={"id", "tipo", "fecha_hora", "descripcion", "categoria_id", "paciente_id", "zona_id", "operador_id"},
 * @OA\Xml(name="Aviso"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="tipo", type="string", readOnly="true", description="Tipo de aviso", example="puntual"),
 * @OA\Property(property="fecha_hora", type="string", readOnly="true", format="date-time", description="Fecha y hora del aviso", example="2022-01-01 12:00:00"),
 * @OA\Property(property="descripcion", type="string", readOnly="true", description="Descripción del aviso", example="Aviso de rutina"),
 * @OA\Property(property="completado", type="boolean", readOnly="true", description="Indica si el aviso fue completado", example="false"),
 * @OA\Property(property="fecha_completado", type="string", readOnly="true", format="date-time", description="Fecha y hora de cuando se completo el aviso", example="null"),
 * @OA\Property(property="categoria_id", type="integer", readOnly="true", description="Id de la categoría", example="1"),
 * @OA\Property(property="paciente_id", type="integer", readOnly="true", description="Id del paciente", example="1"),
 * @OA\Property(property="zona_id", type="integer", readOnly="true", description="Id de la zona", example="1"),
 * @OA\Property(property="operador_id", type="integer", readOnly="true", description="Id del operador", example="1"),
 * )
 */
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
        'zona_id',
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

    public function zona()
    {
        return $this->belongsTo(Zona::class, 'zona_id');
    }
}
