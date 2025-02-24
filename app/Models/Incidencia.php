<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paciente;
use App\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 * required={"id", "paciente_id", "descripcion"},
 * @OA\Xml(name="Incidencia"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="paciente_id", type="integer", readOnly="true", description="Paciente ID", example="1"),
 * @OA\Property(property="descripcion", type="string", readOnly="true", description="Descripción de la incidencia", example="Se cayó en la ducha"),
 * )
 */
class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencias';

    protected $fillable = [
        'paciente_id',
        'descripcion',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function operador()
    {
        return $this->belongsTo(User::class);
    }
}
