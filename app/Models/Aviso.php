<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    use HasFactory;

    protected $fillable = [
        'periocidad',
        'fecha_hora',
        'categoria_id'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime'
    ];

    public function categoria()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }
}
