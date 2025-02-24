<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreZoneRequest",
 *     description="Validación para la creación de zonas",
 *     required={"nombre", "codigo"},
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         maxLength=255,
 *         description="Nombre único de la zona",
 *         example="Zona Norte"
 *     ),
 *     @OA\Property(
 *         property="codigo",
 *         type="string",
 *         maxLength=50,
 *         description="Código único de la zona",
 *         example="ZN-001"
 *     ),
 *     @OA\Property(
 *         property="activa",
 *         type="boolean",
 *         description="Estado de la zona (activa o inactiva)",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="estado_texto",
 *         type="string",
 *         nullable=true,
 *         description="Descripción del estado de la zona",
 *         example="En mantenimiento"
 *     )
 * )
 */
class StoreZoneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255|unique:zonas',
            'codigo' => 'required|string|max:50|unique:zonas',
            'activa' => 'boolean',
            'estado_texto' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'nombre.unique' => 'Ya existe una zona con este nombre',
            'codigo.required' => 'El código es obligatorio',
            'codigo.max' => 'El código no puede tener más de 50 caracteres',
            'codigo.unique' => 'Ya existe una zona con este código',
            'activa.boolean' => 'El campo activo debe ser verdadero o falso',
        ];
    }
}
