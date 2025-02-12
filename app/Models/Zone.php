<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Accessors
    public function getStatusTextAttribute(): string
    {
        return $this->active ? 'Activa' : 'Inactiva';
    }

    public function getOperatorCountAttribute(): int
    {
        return $this->operators()->count();
    }

    // Mutators
    protected function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeWithOperators(Builder $query): Builder
    {
        return $query->whereHas('operators');
    }

    public function scopeWithoutOperators(Builder $query): Builder
    {
        return $query->whereDoesntHave('operators');
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function operators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'operator_zone')
                    ->where('role', 'operator')
                    ->withTimestamps();
    }
}
