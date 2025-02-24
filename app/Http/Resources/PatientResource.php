<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PatientResource",
 *     description="Esquema del recurso Paciente",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador único del paciente",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre completo del paciente",
 *         example="María López"
 *     ),
 *     @OA\Property(
 *         property="fecha_nacimiento",
 *         type="string",
 *         format="date",
 *         description="Fecha de nacimiento del paciente",
 *         example="1985-04-12"
 *     ),
 *     @OA\Property(
 *         property="direccion",
 *         type="string",
 *         description="Dirección del paciente",
 *         example="Calle Falsa 123, Madrid"
 *     ),
 *     @OA\Property(
 *         property="ciudad",
 *         type="string",
 *         nullable=true,
 *         description="Ciudad de residencia del paciente",
 *         example="Madrid"
 *     ),
 *     @OA\Property(
 *         property="codigo_postal",
 *         type="string",
 *         maxLength=10,
 *         nullable=true,
 *         description="Código postal del paciente",
 *         example="28080"
 *     ),
 *     @OA\Property(
 *         property="dni",
 *         type="string",
 *         maxLength=20,
 *         description="DNI único del paciente",
 *         example="12345678A"
 *     ),
 *     @OA\Property(
 *         property="tarjeta_sanitaria",
 *         type="string",
 *         maxLength=50,
 *         description="Número de tarjeta sanitaria único",
 *         example="TS1234567890"
 *     ),
 *     @OA\Property(
 *         property="telefono",
 *         type="string",
 *         maxLength=20,
 *         description="Número de teléfono del paciente",
 *         example="+34 612 345 678"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         nullable=true,
 *         maxLength=100,
 *         description="Correo electrónico del paciente",
 *         example="maria.lopez@example.com"
 *     ),
 *     @OA\Property(
 *         property="zona_id",
 *         type="integer",
 *         description="ID de la zona asignada al paciente",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="situacion_personal",
 *         type="string",
 *         nullable=true,
 *         description="Situación personal del paciente",
 *         example="Vive solo"
 *     ),
 *     @OA\Property(
 *         property="estado_salud",
 *         type="string",
 *         nullable=true,
 *         description="Estado de salud actual del paciente",
 *         example="Hipertensión"
 *     ),
 *     @OA\Property(
 *         property="condicion_vivienda",
 *         type="string",
 *         nullable=true,
 *         description="Condición de la vivienda del paciente",
 *         example="Vivienda propia en buen estado"
 *     ),
 *     @OA\Property(
 *         property="nivel_autonomia",
 *         type="string",
 *         nullable=true,
 *         description="Nivel de autonomía del paciente",
 *         example="Autónomo"
 *     ),
 *     @OA\Property(
 *         property="situacion_economica",
 *         type="string",
 *         nullable=true,
 *         description="Situación económica del paciente",
 *         example="Media-baja"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación del paciente",
 *         example="2025-02-23 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización del paciente",
 *         example="2025-02-24 12:00:00"
 *     )
 * )
 */

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'fecha_nacimiento' => $this->fecha_nacimiento ? $this->fecha_nacimiento->format('Y-m-d') : null,
            'direccion' => $this->direccion,
            'ciudad' => $this->ciudad,
            'codigo_postal' => $this->codigo_postal,
            'dni' => $this->dni,
            'tarjeta_sanitaria' => $this->tarjeta_sanitaria,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'zona_id' => $this->zona_id,
            'situacion_personal' => $this->situacion_personal,
            'estado_salud' => $this->estado_salud,
            'condicion_vivienda' => $this->condicion_vivienda,
            'nivel_autonomia' => $this->nivel_autonomia,
            'situacion_economica' => $this->situacion_economica,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
