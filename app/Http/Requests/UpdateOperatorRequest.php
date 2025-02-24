<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateOperatorRequest",
 *     description="Validación para la actualización de operadores",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         description="Nombre completo del operador",
 *         example="Juan Pérez"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         maxLength=255,
 *         description="Correo electrónico único del operador",
 *         example="juan.perez@example.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         minLength=8,
 *         description="Contraseña del operador (mínimo 8 caracteres)",
 *         example="SecurePass123"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         maxLength=20,
 *         description="Número de teléfono del operador",
 *         example="+34 612 345 678"
 *     ),
 *     @OA\Property(
 *         property="shift",
 *         type="string",
 *         enum={"morning", "afternoon", "night"},
 *         description="Turno asignado al operador",
 *         example="morning"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"active", "inactive", "on_leave"},
 *         description="Estado actual del operador",
 *         example="active"
 *     ),
 *     @OA\Property(
 *         property="hire_date",
 *         type="string",
 *         format="date",
 *         description="Fecha de contratación del operador",
 *         example="2023-05-10"
 *     ),
 *     @OA\Property(
 *         property="termination_date",
 *         type="string",
 *         format="date",
 *         nullable=true,
 *         description="Fecha de terminación del contrato (opcional, debe ser posterior a la fecha de contratación)",
 *         example="2025-12-01"
 *     ),
 *     @OA\Property(
 *         property="zone_id",
 *         type="integer",
 *         nullable=true,
 *         description="ID de la zona principal asignada",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="zones",
 *         type="array",
 *         description="Lista de zonas asignadas al operador",
 *         @OA\Items(
 *             type="integer",
 *             description="ID de zona",
 *             example=3
 *         )
 *     )
 * )
 */
class UpdateOperatorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->operator)],
            'password' => 'sometimes|required|string|min:8',
            'phone' => 'sometimes|required|string|max:20',
            'shift' => 'sometimes|required|string|in:morning,afternoon,night',
            'status' => 'sometimes|required|string|in:active,inactive,on_leave',
            'hire_date' => 'sometimes|required|date',
            'termination_date' => 'nullable|date|after:hire_date',
            'zone_id' => 'nullable|exists:zones,id',
            'zones' => 'sometimes|required|array',
            'zones.*' => 'required|exists:zones,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.max' => 'El teléfono no puede tener más de 20 caracteres',
            'shift.required' => 'El turno es obligatorio',
            'shift.in' => 'El turno debe ser morning, afternoon o night',
            'status.required' => 'El estado es obligatorio',
            'status.in' => 'El estado debe ser active, inactive o on_leave',
            'hire_date.required' => 'La fecha de contratación es obligatoria',
            'hire_date.date' => 'La fecha de contratación debe ser una fecha válida',
            'termination_date.date' => 'La fecha de terminación debe ser una fecha válida',
            'termination_date.after' => 'La fecha de terminación debe ser posterior a la fecha de contratación',
            'zonas.required' => 'Debe asignar al menos una zona',
            'zonas.array' => 'Las zonas deben ser un array',
            'zonas.*.exists' => 'Una o más zonas seleccionadas no existen',
        ];
    }
}
