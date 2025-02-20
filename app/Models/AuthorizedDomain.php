<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizedDomain extends Model
{
    protected $fillable = ['domain', 'active', 'description'];

    protected $casts = [
        'active' => 'boolean',
    ];
}
