<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Contacto",
 *     description="Contacto de un paciente",
 *     @OA\Xml(name="Contacto"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="paciente_id", type="integer", readOnly="true", description="Id del paciente", example="1"),
 *     @OA\Property(property="nombre", type="string", readOnly="true", description="Nombre del contacto", example="John Doe"),
 *     @OA\Property(property="apellido", type="string", readOnly="true", description="Apellido del contacto", example="Doe"),
 *     @OA\Property(property="telefono", type="string", readOnly="true", description="Tel\u00e9fono del contacto", example="123-456-789"),
 *     @OA\Property(property="relacion", type="string", readOnly="true", description="Relaci\u00f3n del contacto con el paciente", example="Hijo"),
 *     @OA\Property(property="direccion", type="string", readOnly="true", description="Direcci\u00f3n del contacto", example="Calle 1, 2, 3"),
 *     @OA\Property(property="disponibilidad", type="string", readOnly="true", description="Disponibilidad del contacto", example="Ma\u00f1ana"),
 *     @OA\Property(property="tiene_llaves", type="boolean", readOnly="true", description="Tiene llaves de la vivienda del paciente", example="true"),
 *     @OA\Property(property="nivel_prioridad", type="integer", readOnly="true", description="Nivel de prioridad del contacto", example="1"),
 * )
 */
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
