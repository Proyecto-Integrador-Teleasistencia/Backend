<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'fecha_nacimiento',
        'direccion',
        'ciudad',
        'codigo_postal',
        'dni',
        'tarjeta_sanitaria',
        'telefono',
        'email',
        'zona_id',
        'situacion_personal',
        'estado_salud',
        'condicion_vivienda',
        'nivel_autonomia',
        'situacion_economica'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accesores
    public function getEdadAttribute(): int
    {
        return $this->fecha_nacimiento->age;
    }

    public function getDireccionCompletaAttribute(): string
    {
        return "{$this->direccion} (Zona: {$this->zona->nombre})";
    }

    // Mutadores
    protected function setDniAttribute(string $value): void
    {
        $this->attributes['dni'] = strtoupper($value);
    }

    protected function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower($value);
    }

    // Alcances
    public function scopeEnZona(Builder $query, int $zonaId): Builder
    {
        return $query->where('zona_id', $zonaId);
    }

    public function scopePacientesMayores(Builder $query): Builder
    {
        return $query->whereDate('fecha_nacimiento', '<=', Carbon::now()->subYears(65));
    }

    public function scopeConAlertasPendientes(Builder $query): Builder
    {
        return $query->whereHas('avisos', function ($query) {
            $query->whereNull('completed_at');
        });
    }

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class)->withDefault(['nombre' => 'Sin zona asignada']);
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class);
    }

    public function llamadas(): HasMany
    {
        return $this->hasMany(Llamada::class)->latest();
    }

    public function alertas(): HasMany
    {
        return $this->hasMany(Aviso::class)->latest();
    }
}
