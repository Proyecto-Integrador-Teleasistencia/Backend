<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birth_date',
        'address',
        'dni',
        'health_card',
        'phone',
        'email',
        'zone_id',
        'personal_situation',
        'health_condition',
        'home_condition',
        'autonomy_level',
        'economic_situation'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessors
    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->address} (Zona: {$this->zone->name})";
    }

    // Mutators
    protected function setDniAttribute(string $value): void
    {
        $this->attributes['dni'] = strtoupper($value);
    }

    protected function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower($value);
    }

    // Scopes
    public function scopeInZone(Builder $query, int $zoneId): Builder
    {
        return $query->where('zone_id', $zoneId);
    }

    public function scopeElderlyPatients(Builder $query): Builder
    {
        return $query->whereDate('birth_date', '<=', Carbon::now()->subYears(65));
    }

    public function scopeWithPendingAlerts(Builder $query): Builder
    {
        return $query->whereHas('alerts', function ($query) {
            $query->whereNull('completed_at');
        });
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class)->withDefault(['name' => 'Sin zona asignada']);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(ContactPerson::class)->orderBy('priority_level');
    }
    public function calls(): HasMany
    {
        return $this->hasMany(Call::class)->latest();
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class)->latest();
    }
}
