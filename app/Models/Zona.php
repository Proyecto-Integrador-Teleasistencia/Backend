<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "nombre"},
 *     @OA\Xml(name="Zona"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="nombre", type="string", readOnly="true", description="Nombre de la zona"),
 *     @OA\Property(property="descripcion", type="string", readOnly="true", description="Descripción de la zona"),
 *     @OA\Property(property="pacientes", type="array", readOnly="true", description="Pacientes en esta zona", @OA\Items(ref="#/components/schemas/Paciente")),
 *     @OA\Property(property="operadores", type="array", readOnly="true", description="Operadores asignados a esta zona", @OA\Items(ref="#/components/schemas/User")),
 * )
 */
class Zona extends Model
{
    use HasFactory;

    protected $table = 'zonas';

    protected $fillable = [
        'nombre',
        'codigo',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Accessors
    public function getEstadoTextoAttribute(): string
    {
        return $this->activa ? 'Activa' : 'Inactiva';
    }

    public function getNumeroOperadoresAttribute(): int
    {
        return $this->operators()->count();
    }

    // Mutators
    protected function setNombreAttribute(string $value): void
    {
        $this->attributes['nombre'] = ucfirst(strtolower($value));
    }

    protected function setCodigoAttribute(string $value): void
    {
        $this->attributes['codigo'] = strtoupper($value);
    }

    // Scopes
    public function scopeActiva(Builder $query): Builder
    {
        return $query->where('activa', true);
    }

    public function scopeConOperadores(Builder $query): Builder
    {
        return $query->whereHas('operators');
    }

    public function scopeSinOperadores(Builder $query): Builder
    {
        return $query->whereDoesntHave('operators');
    }

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class);
    }

    public function operators(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'operator');
    }
}
