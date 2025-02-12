<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Call extends Model
{
    use HasFactory;

    const TYPE_INCOMING = 'incoming';
    const TYPE_OUTGOING = 'outgoing';

    protected $fillable = [
        'datetime',
        'description',
        'type',
        'scheduled',
        'operator_id',
        'patient_id',
        'category_id',
        'alert_id'
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'scheduled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Accessors
    public function getCallTypeFormattedAttribute(): string
    {
        return $this->type === self::TYPE_INCOMING ? 'Entrante' : 'Saliente';
    }

    public function getIsRecentAttribute(): bool
    {
        return $this->datetime->diffInHours(Carbon::now()) < 24;
    }

    // Scopes
    public function scopeIncoming(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_INCOMING);
    }

    public function scopeOutgoing(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_OUTGOING);
    }

    public function scopeScheduledCalls(Builder $query): Builder
    {
        return $query->where('scheduled', true)
                     ->where('datetime', '>', Carbon::now());
    }

    public function scopeByOperator(Builder $query, int $operatorId): Builder
    {
        return $query->where('operator_id', $operatorId);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id')->withDefault(['name' => 'Operador no asignado']);

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class)->withDefault(['name' => 'Paciente no encontrado']);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault(['name' => 'Sin categoría']);
    }

    public function alert(): BelongsTo
    {
        return $this->belongsTo(Alert::class);
    }
}
