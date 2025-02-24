<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdatePatientRequest",
 *     description="Validación para la actualización de pacientes",
 *     required={"nombre", "fecha_nacimiento", "direccion", "dni", "tarjeta_sanitaria", "telefono", "zona_id"},
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         maxLength=100,
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
 *     )
 * )
 */
class UpdatePatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string',
            'ciudad' => 'nullable|string',
            'codigo_postal' => 'nullable|string|max:10',
            'dni' => [
                'required',
                'string',
                Rule::unique('pacientes')->ignore($this->route('paciente')),
                'max:20'
            ],
            'tarjeta_sanitaria' => [
                'required',
                'string',
                Rule::unique('pacientes')->ignore($this->route('paciente')),
                'max:50'
            ],
            'telefono' => 'required|string|max:20',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:100',
                Rule::unique('pacientes')->ignore($this->route('paciente'))
            ],
            'zona_id' => [
                'required',
                'exists:zonas,id',
                function ($attribute, $value, $fail) {
                    if ($value != auth()->user()->zona_id) {
                        $fail('La zona del paciente debe ser la misma que la del usuario que lo crea.');
                    }
                },
            ],
            'situacion_personal' => 'nullable|string',
            'estado_salud' => 'nullable|string',
            'condicion_vivienda' => 'nullable|string',
            'nivel_autonomia' => 'nullable|string',
            'situacion_economica' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'direccion.required' => 'La dirección es obligatoria',
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Este DNI ya está registrado',
            'tarjeta_sanitaria.required' => 'La tarjeta sanitaria es obligatoria',
            'tarjeta_sanitaria.unique' => 'Esta tarjeta sanitaria ya está registrada',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'zona_id.required' => 'La zona es obligatoria',
            'zona_id.exists' => 'La zona seleccionada no existe',
            'zona_id.same' => 'La zona del paciente no puede ser la misma que la del usuario que lo crea.',
            'situacion_personal' => 'nullable|string',
            'estado_salud' => 'nullable|string',
            'condicion_vivienda' => 'nullable|string',
            'nivel_autonomia' => 'nullable|string',
            'situacion_economica' => 'nullable|string',
        ];
    }
}
