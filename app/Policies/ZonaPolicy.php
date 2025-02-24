<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Auth\Access\Response;

class ZonaPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Zone $zone): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Zone $zone): bool
    {
        return $user->role === 'admin';
    }
}
