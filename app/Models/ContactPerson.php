<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'phone',
        'relationship'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
