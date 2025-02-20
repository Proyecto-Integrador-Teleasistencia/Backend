<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Llamada extends Model
{
    use HasFactory;

    protected $table = 'llamadas';

    protected $fillable = [
        'fecha_hora',
        'descripcion',
        'tipo_llamada',
        'duracion',
        'estado',
        'motivo',
        'operador_id',
        'paciente_id',
        'categoria_id',
        'subcategoria_id'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'duracion' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Accessors
    public function getDuracionFormateadaAttribute(): string
    {
        $minutos = floor($this->duracion / 60);
        $segundos = $this->duracion % 60;
        return sprintf('%02d:%02d', $minutos, $segundos);
    }

    public function getEsRecienteAttribute(): bool
    {
        return $this->fecha_hora->diffInHours(Carbon::now()) < 24;
    }

    // Scopes
    public function scopeEntrantes(Builder $query): Builder
    {
        return $query->where('tipo_llamada', 'entrante');
    }

    public function scopeSalientes(Builder $query): Builder
    {
        return $query->where('tipo_llamada', 'saliente');
    }

    public function scopeCompletadas(Builder $query): Builder
    {
        return $query->where('estado', 'completada');
    }

    public function scopePorOperador(Builder $query, int $operadorId): Builder
    {
        return $query->where('operador_id', $operadorId);
    }

    public function operador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operador_id')->withDefault(['nombre' => 'Operador no asignado']);
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class)->withDefault(['nombre' => 'Paciente no encontrado']);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class)->withDefault(['nombre' => 'Sin categoría']);
    }

    public function subcategoria(): BelongsTo
    {
        return $this->belongsTo(Subcategoria::class)->withDefault(['nombre' => 'Sin subcategoría']);
    }
}
