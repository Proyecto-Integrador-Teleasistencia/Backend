<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizedEmail extends Model
{
    protected $fillable = ['email', 'active', 'description'];

    protected $casts = [
        'active' => 'boolean',
    ];
}
