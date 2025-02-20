<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        return $this->operator()->count();
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
        return $query->whereHas('operator');
    }

    public function scopeSinOperadores(Builder $query): Builder
    {
        return $query->whereDoesntHave('operator');
    }

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class);
    }

    public function operator(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'zonas_gestion')
                    ->where('role', 'operator')
                    ->withTimestamps();
    }
}
